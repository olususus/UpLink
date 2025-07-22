#!/bin/bash

# Laravel Forge Deployment Script
# This script is optimized for Laravel Forge but can be used on any server

set -e

echo "🚀 Starting deployment..."

# Navigate to site directory
cd $FORGE_SITE_PATH

# Pull the latest changes
echo "📥 Pulling latest changes..."
git pull origin $FORGE_SITE_BRANCH

# Install/update composer dependencies
echo "📦 Installing Composer dependencies..."
$FORGE_COMPOSER install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Check if we need to run migrations
if [ -f "database/migrations/*.php" ]; then
    echo "🗄️ Running database migrations..."
    $FORGE_PHP artisan migrate --force
fi

# Install/update NPM dependencies and build assets
if [ -f "package.json" ]; then
    echo "📦 Installing NPM dependencies..."
    npm ci --only=production
    
    echo "🎨 Building frontend assets..."
    npm run build
fi

# Clear and cache configurations
echo "⚡ Optimizing application..."
$FORGE_PHP artisan config:clear
$FORGE_PHP artisan config:cache
$FORGE_PHP artisan route:cache
$FORGE_PHP artisan view:cache

# Restart queues if they're running
if pgrep -f "artisan queue:work" > /dev/null; then
    echo "🔄 Restarting queue workers..."
    $FORGE_PHP artisan queue:restart
fi

# Restart PHP-FPM to ensure opcache is cleared
if command -v sudo >/dev/null 2>&1; then
    sudo service php8.2-fpm reload 2>/dev/null || true
fi

echo "✅ Deployment completed successfully!"

# Optional: Run health check
if [ "$1" = "--health-check" ]; then
    echo "🏥 Running health check..."
    curl -f "$APP_URL/health" > /dev/null && echo "✅ Health check passed" || echo "❌ Health check failed"
fi
