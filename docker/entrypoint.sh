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

# The host's .env, mounted read-only, and the live copy the app actually uses.
ENV_SEED="${ENV_SEED:-/config/.env}"
ENV_LIVE="${ENV_LIVE:-$APP_HOME/storage/env/.env}"
ENV_RESEED="${ENV_RESEED:-false}"

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
# The configuration file — both modes.
#
# The live .env lives in the storage volume, not on the host. The superadmin
# settings screen saves mail, payment gateway and backup settings by rewriting
# .env, and writing through a single-file bind mount is refused outright on
# some Linux hosts (AppArmor, snap-confined Docker) even when the ownership and
# mode are correct. A Docker-managed volume has none of those constraints, and
# persists across restarts, recreates and image rebuilds like uploads do.
#
# ./.env on the host is the seed. It is still what docker compose itself reads
# for DB_*, REDIS_PASSWORD and HTTP_*.
###############################################################################
ENV_DIR="$(dirname "$ENV_LIVE")"
mkdir -p "$ENV_DIR"
chown www-data:www-data "$ENV_DIR"
chmod 750 "$ENV_DIR"

seed_env() {
    # Copy then rename: the rename is atomic within the volume, so a container
    # starting alongside this one can never read a half-written file.
    cp "$ENV_SEED" "$ENV_LIVE.tmp.$$"
    chown www-data:www-data "$ENV_LIVE.tmp.$$"
    chmod 664 "$ENV_LIVE.tmp.$$"
    mv -f "$ENV_LIVE.tmp.$$" "$ENV_LIVE"
}

if [ ! -f "$ENV_LIVE" ]; then
    if [ ! -f "$ENV_SEED" ]; then
        log "FATAL: no configuration found at $ENV_SEED."
        log "       Copy .env.docker.example to .env next to docker-compose.yml;"
        log "       compose mounts it here read-only."
        exit 1
    fi
    log "seeding $ENV_LIVE from the host's .env (first start)"
    seed_env
elif [ "$ENV_RESEED" = "true" ]; then
    log "ENV_RESEED=true — replacing the live .env from the host's copy."
    log "                 Anything saved from the settings screen is discarded."
    seed_env
fi

chown www-data:www-data "$ENV_LIVE"
chmod 664 "$ENV_LIVE"

# Laravel reads base_path('.env'); point that at the volume copy. Both
# is_writable() and file_put_contents() follow symlinks, so the app is unaware.
if [ -L "$APP_HOME/.env" ] && [ "$(readlink "$APP_HOME/.env")" = "$ENV_LIVE" ]; then
    :
elif ln -sfn "$ENV_LIVE" "$APP_HOME/.env" 2>/dev/null; then
    log "linked $APP_HOME/.env -> $ENV_LIVE"
    # The link must be owned by the user that follows it. /var/www/html is
    # world-writable and sticky in the upstream php-fpm image, and Linux's
    # fs.protected_symlinks refuses to follow a symlink in such a directory
    # unless the follower owns it — a root-owned link here would give www-data
    # "Permission denied" on a file it can otherwise read perfectly well.
    chown -h www-data:www-data "$APP_HOME/.env"
else
    log "FATAL: could not replace $APP_HOME/.env with a symlink."
    log "       Something is still bind-mounting it. Remove the"
    log "         ./.env:/var/www/html/.env"
    log "       volume line from docker-compose.yml — the host file now mounts"
    log "       at $ENV_SEED instead — then recreate the containers:"
    log "         docker compose up -d --force-recreate"
    exit 1
fi

###############################################################################
# One-off command: run it and stop here.
###############################################################################
if [ "$MODE" = "oneoff" ]; then
    exec gosu www-data "$@"
fi

###############################################################################
# 1. Configuration sanity
###############################################################################
# The live .env was seeded and chowned above, so these should always pass.
# They stay as a tripwire: Laravel's safeLoad() ignores an unreadable .env
# without a word, and every env() call then falls back to its hardcoded default
# — "Database: forge", "Host: 127.0.0.1", connection refused, and nothing in
# any log to explain it. Better to refuse to start.
if ! gosu www-data test -r "$ENV_LIVE"; then
    log "FATAL: $ENV_LIVE is not readable by www-data (uid 33), the user"
    log "       php-fpm runs as. Laravel would ignore it silently and fall back"
    log "       to config defaults (Database: forge, Host: 127.0.0.1)."
    log "       Current: $(ls -l "$ENV_LIVE" 2>/dev/null)"
    exit 1
fi

# The superadmin settings screen rewrites this file. Not fatal if it cannot —
# the app runs fine, that one screen just refuses to save.
if ! gosu www-data test -w "$ENV_LIVE"; then
    log "WARN: $ENV_LIVE is not writable by www-data. The app reads its"
    log "      configuration normally, but saving on the superadmin settings"
    log "      screen will fail with \"make sure .env file has 644 permission"
    log "      & owned by www-data user\". Check the storage volume's ownership."
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
