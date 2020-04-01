#!/bin/bash
# Create .env from .env.example
cp .env.test.example .env
php artisan config:cache
# Instwll composer
echo "INSTALL COMPOSER"
composer install
composer dump-autoload

# Run command
php artisan larajs:setup
