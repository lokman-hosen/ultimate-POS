# Deploying Ultimate POS with Docker

Seven containers, one command. nginx serves the app, php-fpm runs it, MySQL and
Redis each get their own container, two more cover the queue and the cron
schedule, and Adminer gives you a database browser.

| Service     | Image                    | Purpose                                     | Published        |
|-------------|--------------------------|---------------------------------------------|------------------|
| `web`       | `ultimate-pos-web`       | nginx 1.27 — static files, fastcgi to `app` | yes, `HTTP_PORT` |
| `app`       | `ultimate-pos-app`       | PHP 8.3 FPM — the application               | no               |
| `queue`     | `ultimate-pos-app`       | `php artisan queue:work`                    | no               |
| `scheduler` | `ultimate-pos-app`       | `php artisan schedule:work` (replaces cron) | no               |
| `db`        | `mysql:8.0`              | database                                    | no               |
| `redis`     | `redis:7-alpine`         | cache, sessions, queue                      | no               |
| `adminer`   | `adminer:5`              | database browser                            | localhost only   |

`db` and `redis` sit on an `internal` network with no route off the host at
all — only the application containers and Adminer can reach them.

## Data that survives

Four named volumes. They outlive `restart`, `stop`, `down`, and image rebuilds.
Only `docker compose down -v` or an explicit `docker volume rm` destroys them.

| Volume       | Mounted at                          | Holds |
|--------------|-------------------------------------|-------|
| `uploads`    | `/var/www/html/public/uploads`      | **every uploaded file** — product images, business and invoice logos, documents, CMS media, and the local-disk database backups |
| `storage`    | `/var/www/html/storage`             | logs, compiled views, Passport signing keys, mPDF temp files |
| `db-data`    | `/var/lib/mysql`                    | the database |
| `redis-data` | `/data`                             | Redis append-only file |

`public/uploads` is the one you asked about: `config/filesystems.php` roots the
`local` disk at `public_path('uploads')`, so all uploads and (with
`BACKUP_DISK=local`) the nightly backups land there. It is mounted read-write in
`app`/`queue`/`scheduler` and read-only in `web`, and it is a named volume, so
restarting or rebuilding containers never touches it.

## First deployment

```bash
git clone <your-repo> /opt/ultimate-pos && cd /opt/ultimate-pos
```

**1. Configuration.** One `.env` serves both Laravel and compose:

```bash
cp .env.docker.example .env && chmod 600 .env
```

Edit it. At minimum set `DB_DATABASE`, `DB_USERNAME` (not `root`),
`DB_PASSWORD`, `DB_ROOT_PASSWORD`, `REDIS_PASSWORD`, and the `APP_URL` /
`HTTP_PORT` pair — see [http locally, https on your domain](#http-locally-https-on-your-domain),
which is the thing most likely to bite you first. Keep `APP_ENV=live`:
`app/Console/Kernel.php` only registers the scheduled jobs under that exact
value, not `production`.

**2. Application key.**

```bash
docker compose run --rm --no-deps app php artisan key:generate --show
```

Paste the output into `APP_KEY=` in `.env`. Never change it later on a live
database — encrypted columns and sessions depend on it.

**3. Build and start.**

```bash
docker compose up -d --build
```

**4. Create the schema.** Import the dumps that ship with the app — this is the
route the project's own readme recommends, and the one that works on a clean
database. They are mounted inside the db container at `/backup`:

```bash
docker compose exec -T db sh -c 'mysql -u root -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE" < /backup/ultimatepos.sql'
docker compose exec -T db sh -c 'mysql -u root -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE" < /backup/ultimatepos_super_admin_pos.sql'
```

Running `php artisan migrate` from scratch instead does not currently work on
this codebase: `packages` (and other tables) are created by two migrations at
once — `database/migrations/2026_06_28_113441_create_packages_table.php` and
`Modules/Superadmin/Database/Migrations/2018_06_27_185405_create_packages_table.php`
— and the second one to run fails with "table already exists". That is a
pre-existing conflict in the application, not something Docker introduces. Use
`migrate --force` for *incremental* upgrades after the dump is loaded.

**5. Check.**

```bash
docker compose ps
curl -I http://localhost:8080/          # the app
curl -sf http://localhost:8080/healthz  # nginx alone, stays up while PHP restarts
```

Default login is `admin` / `12345678` — change it immediately.

## TLS

The stack speaks plain HTTP. Put a TLS terminator in front of it — simpler to
renew, and easy to share with other sites on the same host:

```bash
# in .env — stop nginx listening on the public interface
HTTP_BIND=127.0.0.1
HTTP_PORT=8080
APP_URL=https://pos.example.com
SESSION_SECURE_COOKIE=true
```

Then point Caddy, Traefik, host nginx + certbot, or your load balancer at
`127.0.0.1:8080`, forwarding `X-Forwarded-Proto: https`. Two things then line
up on their own:

- nginx trusts `X-Forwarded-*` from private ranges, and maps the forwarded
  scheme to `HTTPS=on` for PHP — so `request()->isSecure()` is true and there
  are no redirect loops. (It maps rather than passes the header through
  verbatim, because PHP treats *any* non-empty `HTTPS` value, `"http"`
  included, as secure.)
- `APP_URL` being `https://` makes the app emit https links, as described
  above.

To terminate TLS inside the stack instead, uncomment the 443 port mapping and
the `docker/nginx/ssl` mount in `docker-compose.yml`, drop your certificates in
`docker/nginx/ssl/`, and uncomment the TLS server block at the bottom of
`docker/nginx/default.conf`.

`app/Http/Middleware/TrustProxies.php` currently sets `$proxies = null`, so
Laravel itself trusts no proxy. That is fine here — nginx has already resolved
the real client IP and the scheme before PHP sees the request. Set it only if
something downstream needs Laravel to parse `X-Forwarded-*` itself.

## Browsing the database (Adminer)

Adminer comes up with the stack on `http://127.0.0.1:8081`. Log in with the
`DB_USERNAME` / `DB_PASSWORD` from `.env` — the server field is already filled
in with `db`. For schema changes and dumps, log in as `root` with
`DB_ROOT_PASSWORD`.

It is deliberately bound to loopback, because a database console reachable from
the internet is a standing invitation. On a remote server, tunnel to it:

```bash
ssh -N -L 8081:127.0.0.1:8081 you@your-server
```

then open `http://localhost:8081` on your own machine. Change `ADMINER_PORT` in
`.env` if 8081 is taken. `ADMINER_BIND=0.0.0.0` publishes it to the world —
only do that behind a VPN or an authenticating proxy.

To leave it out of a deployment entirely:

```bash
docker compose up -d --scale adminer=0
```

## http locally, https on your domain

The app decides this from **one value**: the scheme of `APP_URL`.
`app/Providers/AppServiceProvider.php` does

```php
$url = parse_url(config('app.url'));
if ($url['scheme'] == 'https') {
    \URL::forceScheme('https');
}
```

`forceScheme` rewrites the scheme of every generated URL and nothing else — the
host and port still come from the incoming request. So an `https://` APP_URL
while you browse over plain http gives you exactly this: the page itself loads
(you requested it over http), but every asset comes back as
`https://localhost:8080/css/tailwind/app.css` and fails.

Set it to match how you are actually reaching the site:

| Where | `.env` |
|---|---|
| Local | `APP_URL=http://localhost:8080`, `HTTP_PORT=8080`, `SESSION_SECURE_COOKIE=false` |
| Live on a domain | `APP_URL=https://pos.example.com`, `SESSION_SECURE_COOKIE=true` |

`APP_URL` must include the port whenever it is not 80 or 443, and must agree
with `HTTP_PORT`. Restart afterwards:

```bash
docker compose restart app queue scheduler
```

Nothing in the Docker setup needs to change between the two — the same image
serves both. On the live side, terminate TLS in front of the stack (next
section); nginx already forwards the scheme so PHP sees the request as secure,
and `APP_URL` makes the app emit https links.

## Day-to-day

```bash
docker compose logs -f app                  # application log
docker compose logs -f web                  # access/error log
docker compose exec app php artisan <cmd>   # any artisan command
docker compose exec db mysql -u root -p     # database shell
docker compose restart app queue scheduler  # after a config change
```

Deploying new code:

```bash
git pull
docker compose build
docker compose up -d
docker compose exec app php artisan migrate --force
```

Uploads, storage and the database are untouched by that — they live in volumes.

## When you get a 502

nginx is up but PHP is not answering. Two causes cover almost every case.

**php-fpm isn't running.** `docker compose logs app` will show the entrypoint
stopping on a `FATAL` (empty `APP_KEY`, or MySQL unreachable because the `.env`
credentials no longer match what the `db-data` volume was initialised with —
MySQL only creates `MYSQL_USER` on the *first* start of a fresh volume). Fix the
cause and the container stops looping.

**php-fpm is running and says `ready to handle connections`, but nginx still
refuses.** Then nginx is holding a stale IP. Check it:

```bash
docker compose exec web getent hosts app   # compare with the IP in the error
docker compose restart web
```

This is the classic nginx-in-Docker trap: a literal `fastcgi_pass app:9000` is
resolved once at worker startup and cached forever, so recreating the `app`
container hands it a new IP that nginx never learns about. `default.conf`
avoids it by declaring Docker's embedded DNS (`resolver 127.0.0.11 valid=10s`)
and passing the upstream through a variable, which defers resolution to request
time. If you are running an older build of the web image, rebuild it:

```bash
docker compose build web && docker compose up -d web
```

## Backups

The `scheduler` container runs `backup:run` nightly at 01:30 and `backup:clean`
at 01:00 (both only when `APP_ENV=live`). With `BACKUP_DISK=local` the archives
land inside the `uploads` volume; nginx refuses to serve `.zip`/`.sql` from
`/uploads`, but they are still on the same host as the data they protect. Either
point `BACKUP_DISK` at `s3`/`dropbox`, or copy them off:

```bash
# database
docker compose exec -T db sh -c 'mysqldump -u root -p"$MYSQL_ROOT_PASSWORD" --single-transaction --routines "$MYSQL_DATABASE"' | gzip > pos-$(date +%F).sql.gz

# uploads volume
docker run --rm -v ultimate-pos_uploads:/data:ro -v "$PWD":/out alpine \
  tar czf /out/uploads-$(date +%F).tar.gz -C /data .
```

Restoring the uploads into a fresh volume:

```bash
docker run --rm -v ultimate-pos_uploads:/data -v "$PWD":/in alpine \
  tar xzf /in/uploads-2026-08-29.tar.gz -C /data
docker compose restart app   # the entrypoint re-applies ownership
```

## Notes on how this is put together

**`config:cache` is deliberately not run.** This codebase calls `env()` at
runtime in roughly 135 places — the Stripe/Razorpay/Flutterwave keys in the
subscription blades, `GOOGLE_MAP_API_KEY`, the mail-configured check in
`app/Utils/Util.php`. Laravel's `env()` returns `null` once the config is
cached, so caching it breaks those features quietly. OPcache (256 MB, timestamp
validation off) covers the bulk of the performance difference. `route:cache` is
also skipped, because the module packages register routes in ways that do not
always survive it.

**`.env` is bind-mounted read-only** rather than injected as environment
variables, because that is what Laravel and this app expect, and because compose
reads the same file for `DB_*`, `REDIS_PASSWORD` and `HTTP_*`. One file, no
duplication. Changing it needs `docker compose restart app queue scheduler`.

**`max_input_vars` is raised to 10000.** A large sale or stock adjustment posts
hundreds of line items; PHP's default of 1000 truncates such forms silently.

**Uploads are never executable.** nginx refuses to serve
`.php/.phtml/.sh/.sql/.zip/...` from `/uploads`, only `index.php` is ever handed
to PHP, and everything else returns 404 — the nginx equivalent of the rules the
app ships in `public/.htaccess`, plus a deny on the bundled `/install` wizard.

**Sizing.** `pm.max_children = 20` in `docker/php/www.conf` with a 512 MB PHP
memory limit assumes roughly a 4 GB host; `innodb_buffer_pool_size = 512M` in
`docker/mysql/my.cnf` wants about half the RAM you give MySQL. Raise both
together with the machine.

**Upload size limits** live in two places that must agree:
`upload_max_filesize`/`post_max_size` in `docker/php/php.ini` and
`client_max_body_size` in `docker/nginx/nginx.conf`, both 64M.
