FROM dunglas/frankenphp:latest-php8.2

RUN install-php-extensions pdo_mysql mbstring intl opcache

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

COPY . .

RUN composer run-script post-install-cmd --no-dev 2>/dev/null || true
RUN php bin/console cache:warmup --env=prod

EXPOSE 8080
