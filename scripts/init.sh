#!/bin/sh

print_title() {
    printf "\n\033[37m> \033[3;36m%s\033[0m\n" "$*"
}

print_title "Setup Application ..."

# printf "  %-72s" "Cloning Repository"
git clone -c advice.detachedHead=false --depth=1 -b ${DEPLOY_BRANCH} ${DEPLOY_REPO} .
# printf "[\033[32m  OK  \033[0m]\n"

# printf "  %-72s" "Installing Composer Dependencies"
composer install  --no-interaction --prefer-dist --optimize-autoloader --no-suggest --no-cache --no-dev
# printf "[\033[32m  OK  \033[0m]\n"

# printf "  %-72s" "Syncing Storage with Host"
rsync -qru /app/storage/* /storage/
rm -rf /app/storage
ln -s /storage/ /app/storage
# printf "[\033[32m  OK  \033[0m]\n"

if [ "${DB_CONNECTION}" = "sqlite" ]; then
    # printf "  %-72s" "Touching SQLite Database File"
    touch ${DB_DATABASE}
    # printf "[\033[32m  OK  \033[0m]\n"
fi

if [ -z "$APP_KEY" ]; then
    # printf "  %-72s" "Generating random APP_KEY"
    export APP_KEY=`php artisan key:generate --show`
    # printf "[\033[32m  OK  \033[0m]\n"
fi

print_title "Deploying Application ..."

php artisan down

git pull origin ${DEPLOY_BRANCH}
composer install --no-interaction --prefer-dist --optimize-autoloader --no-suggest --no-cache --no-dev

npm install
npm run production

php artisan config:cache
php artisan route:cach

php artisan migrate --force

php artisan up

print_title "Starting Webserver ..."
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf