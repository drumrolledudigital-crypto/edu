#!/bin/bash
# Drumroll Production Deployment Script
# Run this on your production server after pulling code

set -e

echo "Starting Drumroll deployment..."

# 1. Pull latest code
echo "Pulling latest code..."
git pull origin main

# 2. Install dependencies (no dev)
echo "Installing composer dependencies..."
composer install --no-dev --optimize-autoloader

# 3. Install npm and build assets
echo "Building frontend assets..."
npm ci
npm run build

# 4. Run migrations
echo "Running database migrations..."
php artisan migrate --force

# 5. Seed roles/permissions if needed
echo "Seeding RBAC..."
php artisan db:seed --class=RBACSeeder --force 2>/dev/null || true

# 6. Cache everything
echo "Caching configuration..."
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache
php artisan icons:cache

# 7. Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link

# 8. Set permissions
echo "Setting permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

# 9. Restart queue workers
echo "Restarting queue workers..."
php artisan queue:restart

# 10. Restart opcache
echo "Restarting PHP-FPM..."
sudo systemctl reload php8.2-fpm 2>/dev/null || true

echo "Deployment complete!"
echo ""
echo "IMPORTANT: Make sure your server has:"
echo "  - Cron job: * * * * * cd /var/www/drumroll && php artisan schedule:run >> /dev/null 2>&1"
echo "  - Supervisor running queue workers (see deployment/supervisor.conf)"
echo "  - Nginx/Apache configured (see deployment/nginx.conf)"
echo "  - SSL certificate installed"
