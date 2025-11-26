
ARG PHP_VERSION=8.2
FROM php:${PHP_VERSION}-cli-alpine
# Build with: docker build --build-arg PHP_VERSION=8.3 -t anvil:php-8.3 .

# Environment for Unraid-style mapping
ENV PUID=1000 \
    PGID=1000 \
    TZ=UTC \
    ANVIL_RUN_MIGRATIONS=true \
    ANVIL_PORT=8000

# Runtime deps
RUN apk add --no-cache \
        tzdata \
        su-exec \
        sqlite-libs

# Build deps + extensions + cleanup
RUN apk add --no-cache --virtual .build-deps \
        oniguruma-dev \
        libxml2-dev \
        sqlite-dev \
    && docker-php-ext-install \
        mbstring \
        xml \
        pdo_mysql \
        pdo_sqlite \
    && apk del --no-cache .build-deps

# App lives here (mounted from host)
WORKDIR /var/www/html

# Copy scripts
COPY bin/ /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh /usr/local/bin/anvil.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["server"]