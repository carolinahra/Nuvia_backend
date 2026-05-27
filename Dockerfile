FROM dunglas/frankenphp:latest-php8.2

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y unzip git && rm -rf /var/lib/apt/lists/*

RUN install-php-extensions pdo_mysql mbstring intl opcache zip

ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-scripts

EXPOSE 8080

CMD ["sh", "-c", "php bin/console cache:warmup --env=prod && frankenphp run --config /app/Caddyfile"]
