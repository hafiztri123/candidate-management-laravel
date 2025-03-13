FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    supervisor

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


WORKDIR /var/www

COPY --chown=www-data:www-data . /var/www

COPY --chown=www-data:www-data .env /var/www/.env


RUN composer install --optimize-autoloader --no-dev
RUN php artisan key:generate --force

RUN chown -R www-data:www-data /var/www

COPY ./nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ./laravel-worker.conf /etc/supervisor/conf.d/

EXPOSE 8080

CMD ["/bin/bash", "-c", "php artisan migrate --force && supervisord && supervisorctl start 'laravel-worker:*' && php artisan db:seed && php-fpm -D && nginx -g 'daemon off;'"]
