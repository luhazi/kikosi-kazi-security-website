#!/usr/bin/env sh
# ── Kikosi Kazi Security — container startup ────────────────────────────────
# Runs on every boot. Migrations & seeders are idempotent, so this is safe to
# run repeatedly. For production, point DB_DATABASE at a PERSISTENT disk so data
# survives redeploys (see DEPLOYMENT.md). Render injects $PORT.

# Do NOT use `set -e`: a single non-critical failure (e.g. a first-boot seed
# race) must not stop the web server from starting and passing the health check.

DB_PATH="${DB_DATABASE:-/var/www/database/database.sqlite}"
PORT="${PORT:-10000}"

echo "→ Ensuring SQLite database exists at: ${DB_PATH}"
mkdir -p "$(dirname "$DB_PATH")"
[ -f "$DB_PATH" ] || touch "$DB_PATH"

echo "→ Discovering packages"
php artisan package:discover --ansi || true

echo "→ Linking public storage"
php artisan storage:link || true

echo "→ Running database migrations"
php artisan migrate --force || echo "⚠ migrate failed (continuing)"

echo "→ Seeding baseline data (roles, admin, starter content — idempotent)"
php artisan db:seed --force || echo "⚠ seed skipped (continuing)"

echo "→ Caching configuration, routes and views"
php artisan optimize || true

echo "→ Starting web server on 0.0.0.0:${PORT}"
exec php artisan serve --host=0.0.0.0 --port="${PORT}"
