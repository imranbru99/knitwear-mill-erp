# Install and deploy

## Local

Requirements: PHP 8.3+ with `mbstring`, `pdo_sqlite` or `pdo_mysql`, `intl`, `gd`. Composer 2. Node is only needed if you rebuild Filament assets (`npm install && npm run build`). The public site is plain CSS in `public/css/mill.css`.

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite   # skip when DB_CONNECTION=mysql
php artisan migrate --seed
php artisan serve
```

## Production (nginx, FastPanel or similar)

1. Point the vhost root at `public/`. Deny access to everything else.
2. Set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` to the real host, `APP_TIMEZONE=Asia/Dhaka`.
3. Use MySQL 8 or MariaDB. Create the database and user, then set `DB_*` in `.env`.
4. Install Redis. Set `CACHE_STORE=redis`, `QUEUE_CONNECTION=redis`, `SESSION_DRIVER=redis`.
5. `php artisan migrate --force && php artisan db:seed --force` the first time only. Later deploys migrate without seeding.
6. `php artisan storage:link && php artisan config:cache && php artisan route:cache && php artisan view:cache`.
7. Supervisor programs:
   - `php artisan horizon` (or `php artisan queue:work --sleep=1 --tries=3`)
   - cron: `* * * * * cd /path && php artisan schedule:run >> /dev/null 2>&1`
8. HTTPS in front of nginx. Trust the proxy if FastPanel terminates TLS.

## Backups

- Database: nightly `mysqldump` (or `sqlite3 .backup` on the demo) kept 30 days, copied off the mill server.
- Files: `storage/app` (tech packs, challan scans, certificates) on the same schedule.
- Restore drill: load the dump into a spare database and hit `/health` and `/up` before calling it good.

## Health

- `/up` — Laravel framework health.
- `/health` — application check that the database answers.
