#!/bin/bash
echo "Running composer install..."
composer install

echo "Generating Laravel application key..."
php artisan key:generate

echo "Running Laravel migrations..."
php artisan migrate

echo "php-fpm running now you can access your weather app"
php-fpm