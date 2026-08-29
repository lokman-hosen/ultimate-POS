# syntax=docker/dockerfile:1.7
#
# Ultimate POS — production image.
#
#   base    php 8.3-fpm + every extension the app and its packages need
#   vendor  composer dependencies (no dev) + optimised autoloader
#   app     runtime php-fpm image (target: app)
#   web     nginx serving public/ and proxying php to the app container
#
# Build both runtime targets with: docker compose build

###############################################################################
# base
###############################################################################
FROM php:8.3-fpm-bookworm AS base

ENV APP_HOME=/var/www/html \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_MEMORY_LIMIT=-1

# install-php-extensions pulls in the right system libraries per extension.
COPY --from=mlocati/php-extension-installer:2 /usr/bin/install-php-extensions /usr/local/bin/

# gd/zip/intl  -> dompdf, mpdf, milon/barcode, maatwebsite/excel, libphonenumber
# bcmath/exif  -> money maths, image orientation
# pcntl        -> queue worker signal handling
# redis        -> cache/session/queue driver
# pdo_mysql    -> the database
RUN install-php-extensions \
        bcmath \
        exif \
        gd \
        intl \
        opcache \
        pcntl \
        pdo_mysql \
        redis \
        sockets \
        zip \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
        default-mysql-client \
        procps \
        gosu \
        tzdata \
    && rm -rf /var/lib/apt/lists/*

WORKDIR ${APP_HOME}

###############################################################################
# vendor
###############################################################################
FROM base AS vendor

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip \
    && rm -rf /var/lib/apt/lists/*

# Dependencies first, so editing app code does not re-resolve the whole tree.
COPY composer.json composer.lock* ./
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-autoloader \
        --prefer-dist \
        --no-interaction

# Then the source, then the production autoloader.
COPY . ./

# storage/ is excluded from the build context (it holds the Passport keys and
# host-local logs), but the autoloader dump runs package:discover, which boots
# the app — and AppServiceProvider touches Blade, which needs a compiled-view
# path. Recreate the skeleton before dumping.
#
# custom_views is listed as a view path by config/view.php but is not in the
# repository; without it, view:cache aborts with a DirectoryNotFoundException.
RUN mkdir -p \
        storage/app/public \
        storage/app/pdf \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
        custom_views \
    && composer dump-autoload --no-dev --optimize --no-interaction

###############################################################################
# app — php-fpm
###############################################################################
FROM base AS app

COPY docker/php/php.ini      /usr/local/etc/php/conf.d/zz-ultimate-pos.ini
COPY docker/php/www.conf     /usr/local/etc/php-fpm.d/zz-ultimate-pos.conf
COPY docker/entrypoint.sh    /usr/local/bin/entrypoint

COPY --from=vendor --chown=www-data:www-data ${APP_HOME} ${APP_HOME}

RUN chmod +x /usr/local/bin/entrypoint \
    && mkdir -p \
        ${APP_HOME}/bootstrap/cache \
        ${APP_HOME}/public/uploads \
        ${APP_HOME}/storage \
    && chown -R www-data:www-data ${APP_HOME}/bootstrap/cache ${APP_HOME}/public/uploads ${APP_HOME}/storage

EXPOSE 9000

# php-fpm answers on 9000 as soon as it can accept work.
HEALTHCHECK --interval=30s --timeout=5s --start-period=40s --retries=3 \
    CMD php -r 'exit(@fsockopen("127.0.0.1", 9000) ? 0 : 1);'

ENTRYPOINT ["entrypoint"]
CMD ["php-fpm", "--nodaemonize"]

###############################################################################
# web — nginx
###############################################################################
FROM nginx:1.27-alpine AS web

COPY docker/nginx/nginx.conf   /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# nginx serves the static half of public/ itself; PHP goes to the app container
# over fastcgi, so both images agree on /var/www/html as the document root.
COPY --from=vendor /var/www/html/public /var/www/html/public

RUN mkdir -p /var/www/html/public/uploads /var/www/html/storage/app/public

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD wget -qO- http://127.0.0.1/healthz >/dev/null 2>&1 || exit 1
