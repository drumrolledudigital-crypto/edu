# Drumroll — Production Hosting Setup Guide

## Server Requirements
- PHP 8.2+ (with extensions: mbstring, xml, curl, zip, bcmath, gd, openssl, pdo_mysql)
- MySQL 8.0+ or MariaDB 10.6+
- Node.js 18+ (for asset building)
- Composer 2+
- Nginx or Apache with mod_rewrite

## Quick Setup

### 1. Clone & Install
```bash
git clone <repo-url> /var/www/drumroll
cd /var/www/drumroll
composer setup
```

### 2. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with production values:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://yourdomain.com`
- `DB_PASSWORD=<strong-password>`
- `SESSION_SECURE_COOKIE=true`
- `SESSION_ENCRYPT=true`
- `STRIPE_KEY=pk_live_...`
- `STRIPE_SECRET=sk_live_...`
- `GOOGLE_CLIENT_ID=...`
- `GOOGLE_CLIENT_SECRET=...`

### 3. Database Setup
```sql
CREATE DATABASE drumroll CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'drumroll_user'@'localhost' IDENTIFIED BY 'your_strong_password';
GRANT ALL PRIVILEGES ON drumroll.* TO 'drumroll_user'@'localhost';
FLUSH PRIVILEGES;
```

### 4. Run Migrations
```bash
php artisan migrate --force
php artisan db:seed --class=RBACSeeder
```

### 5. Build Assets
```bash
npm ci
npm run build
```

### 6. Cache Everything
```bash
composer deploy
```

### 7. Set Permissions
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
php artisan storage:link
```

## Server Configuration

### Nginx
Copy `deployment/nginx.conf` to `/etc/nginx/sites-available/drumroll`:
```bash
cp deployment/nginx.conf /etc/nginx/sites-available/drumroll
ln -s /etc/nginx/sites-available/drumroll /etc/nginx/sites-enabled/
rm /etc/nginx/sites-enabled/default
nginx -t && systemctl reload nginx
```

### SSL (Let's Encrypt)
```bash
apt install certbot python3-certbot-nginx
certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

### Queue Workers (Supervisor)
```bash
cp deployment/supervisor.conf /etc/supervisor/conf.d/drumroll-queue.conf
cp deployment/supervisor-scheduler.conf /etc/supervisor/conf.d/drumroll-scheduler.conf
supervisorctl reread && supervisorctl update
```

### Cron Job (Alternative to Supervisor for scheduler)
```bash
crontab -e
# Add:
* * * * * cd /var/www/drumroll && php artisan schedule:run >> /dev/null 2>&1
```

## Post-Deployment Checklist

- [ ] `APP_DEBUG=false` in `.env`
- [ ] `APP_URL=https://yourdomain.com` in `.env`
- [ ] `SESSION_SECURE_COOKIE=true` in `.env`
- [ ] `SESSION_ENCRYPT=true` in `.env`
- [ ] Database user is NOT root
- [ ] Database password is strong
- [ ] SSL certificate installed
- [ ] Queue worker running (Supervisor)
- [ ] Scheduler running (Supervisor or cron)
- [ ] `storage/` directory writable by web server
- [ ] `bootstrap/cache/` directory writable by web server
- [ ] Stripe keys are LIVE keys (pk_live_, sk_live_)
- [ ] Google OAuth credentials set in Admin Settings
- [ ] SMTP credentials configured in Admin Settings
- [ ] `composer deploy` has been run

## Running artisan commands in production
```bash
php artisan tinker          # Interactive REPL
php artisan route:list      # List routes
php artisan migrate:status  # Check migration status
php artisan queue:work      # Manual queue worker (use Supervisor instead)
```

## Troubleshooting

### 500 Error
```bash
tail -f storage/logs/laravel.log
```

### Permission Denied
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Queue Jobs Not Processing
```bash
supervisorctl status
php artisan queue:work --stop-when-empty
```

### Cache Issues
```bash
php artisan optimize:clear
composer deploy
```
