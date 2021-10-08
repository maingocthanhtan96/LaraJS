#!/bin/bash

# Create .env from .env.example
cp .env.example .env

# Instwll composer
echo "INSTALL COMPOSER"
composer install
composer dump-autoload

# Cache config
php artisan config:cache

# Run command
php artisan larajs:setup
