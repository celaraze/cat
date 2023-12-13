FROM php:latest
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt update && apt -y upgrade \
    && apt install -y sqlite3 wget libnss3-tools libfreetype-dev libjpeg62-turbo-dev libpng-dev libicu-dev zlib1g-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install zip \
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install opcache

WORKDIR /var/www
COPY . /var/www/

RUN composer install -vvv \
    && if [ -f /var/www/database/data/database.sqlite ]; then rm /var/www/database/data/database.sqlite; fi \
    && touch /var/www/database/data/database.sqlite \
    && cp .env.docker .env \
    && php artisan cat:install \
    && wget https://github.com/dunglas/frankenphp/releases/download/v1.0.0/frankenphp-linux-x86_64 -O /var/www/cat \
    && chmod +x ./cat

CMD [ "./cat","run" ]
