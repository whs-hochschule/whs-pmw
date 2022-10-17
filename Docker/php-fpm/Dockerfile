FROM php:7.4-fpm

RUN docker-php-ext-install pdo pdo_mysql

RUN apt-get update && apt-get install -y \
    libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
	&& docker-php-ext-enable imagick
RUN rm -rf /var/lib/apt/lists/*

RUN echo "date.timezone=Europe/Berlin" > $PHP_INI_DIR/conf.d/date_timezone.ini