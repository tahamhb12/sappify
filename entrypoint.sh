#!/bin/bash

# migrate
php artisan migrate --force

# optimize
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize:clear

# start server
php artisan octane:frankenphp
