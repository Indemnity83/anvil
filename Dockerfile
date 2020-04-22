FROM node:latest as build-assets
WORKDIR /app
COPY package*.json tailwind.config.js webpack.mix.js ./
RUN npm install
COPY resources/js ./resources/js
COPY resources/css ./resources/css
RUN npm run production

FROM alpine
LABEL maintainer="Kyle Klaus <kklaus@indemnity83.com>"

ARG VERSION
ENV ANVIL_VERSION = $VERSION
ENV UPLOAD_LIMIT=5M

# Install packages
RUN apk --no-cache add git composer nginx supervisor npm \
    php php7-fpm php7-json php7-mbstring php7-iconv php7-pcntl php7-posix php7-sodium \
    php7-session php7-xml php7-curl php7-fileinfo php7-gd php7-intl php7-zip \
    php7-simplexml php7-pdo php7-sqlite3 php7-pdo_sqlite php7-exif php7-pdo_mysql \
    php7-pdo_pgsql php7-pdo_odbc php7-dom php7-xmlwriter php7-tokenizer

# Configure NGINX
COPY resources/serve/nginx.conf /etc/nginx/nginx.conf
RUN rm /etc/nginx/conf.d/default.conf

# Configure PHP-FPM
COPY resources/serve/fpm-pool.conf /etc/php7/php-fpm.d/www.conf
COPY resources/serve/php.ini /etc/php7/conf.d/custom.ini

# Configure supervisord
COPY resources/serve/supervisord.conf /etc/supervisord.conf

# Make the application data folder
RUN mkdir /anvil

# Create a group and user
RUN addgroup -S anvil && \
    adduser -S anvil -G anvil

# Make sure files/folders needed by the processes are accessable when they run under the anvil user
RUN chown -R anvil.anvil /anvil && \
    chown -R anvil.anvil /run && \
    chown -R anvil.anvil /var/log/php7 && \
    chown -R anvil.anvil /var/log/nginx && \
    chmod 0751 /var/lib/nginx

# Switch to use a non-root user from here on
USER anvil
WORKDIR /anvil

# Install dependencies
# TODO pull composer install into dedicated image similar to npm
COPY --chown=anvil:anvil composer.json composer.json
RUN composer install --prefer-dist --no-scripts --no-dev --no-autoloader --no-cache

# Copy the compiled js and css into the container
COPY --chown=anvil:anvil --from=build-assets /app/public/js /anvil/public/js
COPY --chown=anvil:anvil --from=build-assets /app/public/css /anvil/public/css

# Copy the environment template
COPY --chown=anvil:anvil ./.env.deploy ./.env

# Copy the init script
COPY --chown=anvil:anvil ./resources/serve/init.sh ./
RUN chmod +x ./init.sh

# Copy codebase
COPY --chown=anvil:anvil . ./

# Finish composer
RUN composer dump-autoload

# Expose the application
EXPOSE 8080-8100
EXPOSE 8888
VOLUME /anvil/storage

# Run the init script on startup
CMD ["/usr/bin/supervisord"]

# Configure a healthcheck to validate that everything is up & running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping
