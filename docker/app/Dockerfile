FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    bash \
    curl \
    git \
    nginx \
    postgresql-client \
    supervisor \
    unzip \
    libpq-dev \
    icu-dev \
    oniguruma-dev \
    && docker-php-ext-install pdo_pgsql pgsql \
    && rm -rf /var/cache/apk/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader

COPY . /app
RUN composer dump-autoload --optimize --no-dev \
    && mkdir -p /run/nginx /var/log/supervisor /app/runtime \
    && chown -R www-data:www-data /app/runtime

COPY docker/app/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/app/php-fpm-www.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/app/supervisord.conf /etc/supervisord.conf
COPY docker/app/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
