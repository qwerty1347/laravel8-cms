#!/bin/bash

echo "Starting initialization script..."

# Laravel 프로젝트의 최종 경로 설정
TARGET_DIR="/var/www/html"

# 볼륨 마운트로 인해 TARGET_DIR에 파일이 존재하면 제거
# if [ -d "$TARGET_DIR" ] && [ "$(ls -A $TARGET_DIR)" ]; then
#     echo "Cleaning up $TARGET_DIR..."
#     rm -rf "$TARGET_DIR"/*
# fi

# Laravel 프로젝트가 이미 설치되어 있는지 확인
if [ ! -f "$TARGET_DIR/artisan" ]; then
    echo "Laravel not found in $TARGET_DIR. Installing..."

    # Laravel 8 설치
    composer create-project --prefer-dist laravel/laravel $TARGET_DIR "8.*"

    echo "Laravel installed successfully in $TARGET_DIR."
else
    echo "Laravel is already installed in $TARGET_DIR."
fi

# 권한 설정
echo "Setting permissions..."
chmod -R 775 $TARGET_DIR/storage $TARGET_DIR/bootstrap/cache

# PHP-FPM 설정 확인
echo "Checking PHP-FPM configuration..."
php-fpm -t

# PHP-FPM 시작
echo "Starting PHP-FPM..."
php-fpm -F

echo "Initialization completed."
