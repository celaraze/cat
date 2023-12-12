FROM php:latest
RUN apt update && apt -y upgrade && \
    apt install -y sqlite3 wget libnss3-tools
WORKDIR /var/www
COPY . /var/www/
RUN rm /var/www/database/data/database.sqlite
RUN touch /var/www/database/data/database.sqlite
RUN cp .env.docker .env
RUN php artisan cat:install

RUN wget https://github.com/dunglas/frankenphp/releases/download/v1.0.0/frankenphp-linux-x86_64 -O /var/www/cat

RUN chmod +x ./cat

CMD [ "./cat","run" ]
