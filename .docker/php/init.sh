#!/bin/bash

echo "Starting initialization script..."

TARGET_DIR="/var/www/html"

if [ ! -f "$TARGET_DIR/artisan" ]; then
    echo "Laravel not found in $TARGET_DIR. Installing..."

    composer create-project --prefer-dist laravel/laravel $TARGET_DIR "8.*"

    echo "Laravel installed successfully in $TARGET_DIR."
else
    echo "Laravel is already installed in $TARGET_DIR."
fi

echo "Setting permissions..."
mkdir -p $TARGET_DIR/storage/logs $TARGET_DIR/bootstrap/cache
chown -R www-data:www-data $TARGET_DIR/storage $TARGET_DIR/bootstrap/cache
chmod -R 775 $TARGET_DIR/storage $TARGET_DIR/bootstrap/cache

echo "Checking PHP-FPM configuration..."
php-fpm -t

echo "Starting PHP-FPM..."
php-fpm -F

echo "Initialization completed."
