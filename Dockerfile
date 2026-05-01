FROM dunglas/frankenphp:latest-php8.2

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y unzip git && rm -rf /var/lib/apt/lists/*

RUN install-php-extensions pdo_mysql mbstring intl opcache zip

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

COPY . .

RUN composer run-script post-install-cmd --no-dev 2>/dev/null || true
RUN php bin/console cache:warmup --env=prod

EXPOSE 8080
