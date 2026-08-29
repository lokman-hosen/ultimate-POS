#!/bin/sh
#
# Ultimate POS container entrypoint.
#
# Runs as root so it can prepare the volume mounts (which start out owned by
# root), then hands over to www-data for everything except the php-fpm master.
#
# Two modes:
#   service  php-fpm, or CONTAINER_ROLE=queue|scheduler — full bootstrap:
#            config checks, wait for MySQL, one-off setup, then run.
#   one-off  anything else, i.e. `docker compose run ... app php artisan ...`.
#            Prepares the writable paths and gets out of the way, so setup
#            commands work before .env is complete and without a database.

set -e

APP_HOME=/var/www/html
CONTAINER_ROLE="${CONTAINER_ROLE:-app}"
AUTO_MIGRATE="${AUTO_MIGRATE:-false}"
DB_WAIT_TIMEOUT="${DB_WAIT_TIMEOUT:-90}"

log() { echo "[entrypoint] $*"; }

case "$1" in
    php-fpm) MODE=service ;;
    *)
        case "$CONTAINER_ROLE" in
            queue|scheduler) MODE=service ;;
            *)               MODE=oneoff  ;;
        esac
        ;;
esac

# Read a key out of the bind-mounted .env, letting a real environment variable
# win when one is set.
env_get() {
    _key="$1"
    _val="$(printenv "$_key" 2>/dev/null || true)"
    if [ -n "$_val" ]; then
        echo "$_val"
        return
    fi
    [ -f "$APP_HOME/.env" ] || return 0
    sed -n "s/^[[:space:]]*${_key}[[:space:]]*=[[:space:]]*//p" "$APP_HOME/.env" \
        | head -n1 \
        | sed -e 's/[[:space:]]*$//' -e 's/^"\(.*\)"$/\1/' -e "s/^'\(.*\)'$/\1/" -e 's/\r$//'
}

# Recursive chown is expensive once uploads holds tens of thousands of files,
# so only do it when the mount root is not already ours — that covers a fresh
# volume and a restored backup, and skips the cost on an ordinary restart.
ensure_owned() {
    _dir="$1"
    [ -d "$_dir" ] || return 0
    if [ "$(stat -c '%U' "$_dir" 2>/dev/null)" != "www-data" ]; then
        log "taking ownership of $_dir"
        chown -R www-data:www-data "$_dir"
    fi
}

###############################################################################
# Writable paths — both modes.
###############################################################################
for dir in \
    "$APP_HOME/storage/app/public" \
    "$APP_HOME/storage/app/pdf" \
    "$APP_HOME/storage/framework/cache/data" \
    "$APP_HOME/storage/framework/sessions" \
    "$APP_HOME/storage/framework/views" \
    "$APP_HOME/storage/logs" \
    "$APP_HOME/bootstrap/cache"
do
    mkdir -p "$dir"
    chown www-data:www-data "$dir"
done

# Upload targets: config/filesystems.php roots the "local" disk at
# public/uploads, and the upload handlers write into these subdirectories.
for dir in business_logos documents img invoice_logos media temp carousel_images cms; do
    mkdir -p "$APP_HOME/public/uploads/$dir"
    chown www-data:www-data "$APP_HOME/public/uploads/$dir"
    [ -f "$APP_HOME/public/uploads/$dir/index.html" ] || : > "$APP_HOME/public/uploads/$dir/index.html"
done
[ -f "$APP_HOME/public/uploads/index.html" ] || : > "$APP_HOME/public/uploads/index.html"

ensure_owned "$APP_HOME/storage"
ensure_owned "$APP_HOME/bootstrap/cache"
ensure_owned "$APP_HOME/public/uploads"

###############################################################################
# One-off command: run it and stop here.
###############################################################################
if [ "$MODE" = "oneoff" ]; then
    exec gosu www-data "$@"
fi

###############################################################################
# 1. Configuration sanity
###############################################################################
if [ ! -f "$APP_HOME/.env" ]; then
    log "FATAL: $APP_HOME/.env is missing."
    log "       Copy .env.docker.example to .env on the host and edit it;"
    log "       compose mounts it into this container read-only."
    exit 1
fi

if [ -z "$(env_get APP_KEY)" ]; then
    log "FATAL: APP_KEY is empty in .env."
    log "       Generate one and paste it in, then start again:"
    log "         docker compose run --rm --no-deps app php artisan key:generate --show"
    log "       Never rotate it on a live database — encrypted columns and"
    log "       existing sessions depend on it."
    exit 1
fi

###############################################################################
# 2. Wait for MySQL
###############################################################################
DB_HOST="$(env_get DB_HOST)"; DB_HOST="${DB_HOST:-db}"
DB_PORT="$(env_get DB_PORT)"; DB_PORT="${DB_PORT:-3306}"

log "waiting for mysql at ${DB_HOST}:${DB_PORT} (up to ${DB_WAIT_TIMEOUT}s)"
waited=0
until mysqladmin ping \
        --host="$DB_HOST" --port="$DB_PORT" \
        --user="$(env_get DB_USERNAME)" --password="$(env_get DB_PASSWORD)" \
        --silent >/dev/null 2>&1
do
    if [ "$waited" -ge "$DB_WAIT_TIMEOUT" ]; then
        log "FATAL: mysql did not become reachable within ${DB_WAIT_TIMEOUT}s."
        exit 1
    fi
    waited=$((waited + 2))
    sleep 2
done
log "mysql is up"

###############################################################################
# 3. One-off setup — the app container only, so the workers never race it.
###############################################################################
if [ "$CONTAINER_ROLE" = "app" ]; then
    # Passport signing keys live in the storage volume so they survive
    # restarts. Regenerating them invalidates every issued API token, so they
    # are only created when genuinely absent.
    if [ ! -f "$APP_HOME/storage/oauth-private.key" ]; then
        log "generating passport keys (none found in the storage volume)"
        gosu www-data php artisan passport:keys --no-interaction || \
            log "WARN: passport:keys failed — API tokens will not work until it does"
    fi

    if [ ! -e "$APP_HOME/public/storage" ]; then
        gosu www-data php artisan storage:link --no-interaction || true
    fi

    if [ "$AUTO_MIGRATE" = "true" ]; then
        log "running migrations (AUTO_MIGRATE=true)"
        gosu www-data php artisan migrate --force --no-interaction
    fi

    # NOTE: `config:cache` is deliberately NOT run. This application calls
    # env() at runtime in ~135 places (the payment gateway keys in the
    # subscription blades, GOOGLE_MAP_API_KEY, the mail-configured check in
    # app/Utils/Util.php). env() returns null once the config is cached, which
    # would break those quietly. OPcache covers most of the difference.
    gosu www-data php artisan view:clear --no-interaction >/dev/null 2>&1 || true
    gosu www-data php artisan view:cache --no-interaction >/dev/null 2>&1 || \
        log "WARN: view:cache failed — views will compile on first request"
fi

###############################################################################
# 4. Hand over
###############################################################################
log "role=${CONTAINER_ROLE} starting: $*"

if [ "$1" = "php-fpm" ]; then
    # The fpm master needs root; its workers drop to www-data (see www.conf).
    exec "$@"
fi
exec gosu www-data "$@"
