#!/bin/sh

mkdir -p storage/app/public
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs

touch storage/database.sqlite

php artisan key:generate --force
php artisan migrate --force
php artisan storage:link
php artisan view:cache
php artisan config:cache
php artisan route:cache
