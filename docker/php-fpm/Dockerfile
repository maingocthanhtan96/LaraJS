FROM php:7.4-fpm

LABEL maintainer="maingocthanhtan96@gmail.com"

WORKDIR /var/www

RUN apt-get update -y
RUN apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions & required packages
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/local/bin --filename=composer

RUN usermod -u 1000 www-data

COPY ./laravel.ini /usr/local/etc/php/conf.d
COPY ./xlaravel.pool.conf /usr/local/etc/php-fpm.d/
COPY ../../ /var/www

# Add crontab
USER root
COPY ./app-entrypoint.sh /
# Add crontab file in the cron directory
COPY ./crontab /etc/cron.d
# Give execution rights on the cron job
RUN chmod -R 644 /etc/cron.d

RUN chmod 0755 /app-entrypoint.sh
ENTRYPOINT ["/app-entrypoint.sh"]

EXPOSE 9000

CMD ["php-fpm"]
