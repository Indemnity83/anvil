FROM alpine

LABEL maintainer="Kyle Klaus <kklaus@indemnity83.com>"

ARG VERSION
ENV ANVIL_VERSION = $VERSION

ENV DEPLOY_BRANCH=master
ENV UPLOAD_LIMIT=5M

ENV APP_ENV=production
ENV APP_DEBUG=false

ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=/storage/database.sqlite

ENV MAIL_DRIVER=log
ENV BROADCAST_DRIVER=log
ENV CACHE_DRIVER=file
ENV QUEUE_CONNECTION=sync
ENV SESSION_DRIVER=file
ENV SESSION_LIFETIME=120

# Install packages
RUN apk --no-cache add git composer nginx supervisor rsync npm \
    php php7-fpm php7-json php7-mbstring php7-iconv php7-pcntl php7-posix php7-sodium \
    php7-session php7-xml php7-curl php7-fileinfo php7-gd php7-intl php7-zip \
    php7-simplexml php7-pdo php7-sqlite3 php7-pdo_sqlite php7-exif php7-pdo_mysql \
    php7-pdo_pgsql php7-pdo_odbc php7-dom php7-xmlwriter php7-tokenizer

# Configure NGINX
COPY config/nginx.conf /etc/nginx/nginx.conf
RUN rm /etc/nginx/conf.d/default.conf

# Configure PHP-FPM
COPY config/fpm-pool.conf /etc/php7/php-fpm.d/www.conf
COPY config/php.ini /etc/php7/conf.d/custom.ini

# Configure supervisord
COPY config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Setup directories
RUN mkdir /app && \
    mkdir /storage

# Add scripts
ADD scripts /usr/local/bin
RUN chmod -R +x /usr/local/bin

# Create a group and user
RUN addgroup -S anvil && \
    adduser -S anvil -G anvil

# Make sure files/folders needed by the processes are accessable when they run under the anvil user
RUN chown -R anvil.anvil /storage && \
    chown -R anvil.anvil /app && \
    chown -R anvil.anvil /run && \
    chown -R anvil.anvil /var/log/php7 && \
    chown -R anvil.anvil /var/log/nginx && \
    chmod 0751 /var/lib/nginx

# Switch to use a non-root user from here on
USER anvil
WORKDIR /app

# Expose the application
EXPOSE 8080
VOLUME /storage

# Run the init script on startup
CMD exec init.sh

# Configure a healthcheck to validate that everything is up & running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping
