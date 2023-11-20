#!/bin/bash
set -e

cd /var/www
env >> /var/www/.env
php artisan clear-compiled
php artisan config:clear
php artisan storage:link
npm run build
php-fpm8.1 -D
nginx -g "daemon off;"

