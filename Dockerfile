FROM node:latest as build-assets
WORKDIR /app
COPY package*.json tailwind.config.js webpack.mix.js ./
RUN npm install
COPY resources/js ./resources/js
COPY resources/css ./resources/css
COPY .env.docker .env
RUN npm run production

FROM composer:latest as build-vendor
WORKDIR /app
COPY composer.* ./
RUN composer install --prefer-dist --no-scripts --no-dev --no-autoloader --no-cache

FROM alpine
LABEL maintainer="Kyle Klaus <kklaus@indemnity83.com>"

ARG VERSION
ENV ANVIL_VERSION=$VERSION
ENV UPLOAD_LIMIT=5M

# Install packages
RUN apk --no-cache add git composer nginx supervisor npm \
    php php7-fpm php7-json php7-mbstring php7-iconv php7-pcntl php7-posix php7-sodium \
    php7-session php7-xml php7-curl php7-fileinfo php7-gd php7-intl php7-zip \
    php7-simplexml php7-pdo php7-sqlite3 php7-pdo_sqlite php7-exif php7-pdo_mysql \
    php7-pdo_pgsql php7-pdo_odbc php7-dom php7-xmlwriter php7-tokenizer

# Copy configurations
COPY docker/rootfs /

# Create a group and user
RUN addgroup -S anvil && \
    adduser -S anvil -G anvil

# Make sure files/folders needed by the processes are accessable when they run under the anvil user
RUN chown -R anvil.anvil /run && \
    chown -R anvil.anvil /var/log/php7 && \
    chown -R anvil.anvil /var/log/nginx && \
    chmod 0751 /var/lib/nginx

# Publish data volume (linked to application storage)
RUN ln -s /home/anvil/storage /data
VOLUME /data

## Copy the application
COPY --chown=anvil:anvil . /app
COPY --chown=anvil:anvil --from=build-vendor /app/vendor /app/vendor
COPY --chown=anvil:anvil --from=build-assets /app/public /app/public
COPY --chown=anvil:anvil .env.docker /app/.env

# Switch to use a non-root user from here on
USER anvil
WORKDIR /app

# Finish composer
RUN composer dump-autoload --quiet

# Expose the application
EXPOSE 8888
EXPOSE 6001
EXPOSE 8080-8100

# Run the init script on startup
CMD ["sh", "-c", "/bin/init && /usr/bin/supervisord"]

# Configure a healthcheck to validate that everything is up & running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping
