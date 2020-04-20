#!/bin/sh

# Clone the repo and branch
git clone --depth=1 --progress --verbose -b $2 $1 .

# Add deployment script
mkdir -p storage/deploy && touch storage/deploy/deploy.sh

# TODO: This is supposed to be conditional
composer install --optimize-autoloader --no-dev

# TODO: These steps assume its a laravel app and are happy path only
cp .env.example .env
php artisan key:generate
