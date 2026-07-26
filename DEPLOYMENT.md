# Deployment Guide — Kikosi Kazi Security

The app ships with a `Dockerfile` and `docker/start.sh` that, on every container
start, will: create the SQLite file → run migrations → seed baseline data
(roles, admin, starter content — all idempotent) → cache config/routes/views →
start the server on port `10000`.

## 1. Required environment variables (set these in your host dashboard)

Do **not** commit real secrets. `.env` is excluded from the image via
`.dockerignore`; the host injects these at runtime.

| Variable | Value |
|---|---|
| `APP_NAME` | `Kikosi Kazi Security` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | `base64:...` (run `php artisan key:generate --show` locally and paste) |
| `APP_URL` | **your real HTTPS domain**, e.g. `https://kikosikazi.co.tz` (not `127.0.0.1`) |
| `DB_CONNECTION` | `sqlite` |
| `DB_DATABASE` | absolute path on the **persistent disk**, e.g. `/var/data/database.sqlite` |
| `SESSION_DRIVER` | `database` (or `file`) |
| `MAIL_*` | your SMTP settings (for OTP + application emails) |

> **APP_URL matters:** asset URLs, generated links, OpenGraph tags and emails all
> use it. Set it to the live `https://` domain.

## 2. ⚠️ Persistence — the most important step

Render/Railway/Fly containers have an **ephemeral filesystem**: it is wiped on
every redeploy. Without a persistent disk you would lose **all data on each
deploy** — jobs, candidates, applications, CMS content, client logos, gallery
photos and uploaded CVs.

You have two options:

**Option A — Persistent disk (keep SQLite)**
1. Add a disk in your host (Render: *Disks → Add Disk*), mount path e.g. `/var/data`.
2. Set `DB_DATABASE=/var/data/database.sqlite`.
3. Move uploads onto the disk too: set `FILESYSTEM_DISK` / storage path to a
   folder under `/var/data`, or symlink `storage/app/public` onto the disk.
   (Simplest: mount the disk at `/var/www/storage/app/public`.)

**Option B — Managed database + object storage (recommended for scale)**
1. Provision a managed **PostgreSQL** or **MySQL** (Render/Railway one click).
   Set `DB_CONNECTION=pgsql|mysql` + `DB_HOST/PORT/DATABASE/USERNAME/PASSWORD`.
2. Store uploads on **S3-compatible** object storage
   (`FILESYSTEM_DISK=s3` + the `AWS_*` keys). Cloudflare R2 / Backblaze B2 work well.

For a real enterprise deployment, **Option B** is the right long-term choice.

## 3. First-time admin login

The `AdminUserSeeder` creates the admin account. Sign in at `/admin/login`
with the credentials defined in that seeder (change the password immediately
after first login). Candidates use `/login`.

## 4. Web server note

`php artisan serve` (single-threaded) is used for simplicity and is fine for a
demo / low traffic. For production traffic, switch to **nginx + php-fpm** (or an
Octane/FrankenPHP image). Ask and this can be provided as an alternative Dockerfile.

## 5. Quick local test of the container

```bash
docker build -t kikosi-security .
docker run -p 10000:10000 --env-file .env kikosi-security
# open http://localhost:10000
```
