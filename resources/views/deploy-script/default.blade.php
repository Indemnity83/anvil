cd {{ $site->path }}
git pull origin {{ $site->repository_branch }}
composer install --no-interaction --prefer-dist --optimize-autoloader

if [ -f artisan ]; then
    php artisan migrate --force
fi
