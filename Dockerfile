FROM composer:2 AS vendor

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

FROM php:8.4-cli-alpine

RUN apk add --no-cache oniguruma-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring bcmath

WORKDIR /app

COPY . .
COPY --from=vendor /app/vendor ./vendor

EXPOSE 8000

CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000