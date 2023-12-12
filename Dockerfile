FROM php:latest
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt update && apt -y upgrade && \
    apt install -y sqlite3 wget libnss3-tools libfreetype-dev libjpeg62-turbo-dev libpng-dev libicu-dev zlib1g-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install zip

WORKDIR /var/www
COPY . /var/www/
RUN composer install -vvv
RUN rm /var/www/database/data/database.sqlite
RUN touch /var/www/database/data/database.sqlite
RUN cp .env.docker .env
RUN php artisan cat:install

RUN wget https://github.com/dunglas/frankenphp/releases/download/v1.0.0/frankenphp-linux-x86_64 -O /var/www/cat

RUN chmod +x ./cat

CMD [ "./cat","run" ]
