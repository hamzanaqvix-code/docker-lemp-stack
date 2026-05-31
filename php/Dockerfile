FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    imagemagick-dev \
    $PHPIZE_DEPS

# Install PHP extensions
RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp && \
    docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mysqli \
        gd \
        zip \
        intl \
        mbstring \
        opcache \
        bcmath \
        exif

# Install Redis extension via PECL
RUN pecl install redis && docker-php-ext-enable redis

# Install imagick via PECL
RUN pecl install imagick && docker-php-ext-enable imagick

# PHP configuration
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.validate_timestamps=1'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.memory_consumption=192'; \
    echo 'opcache.max_wasted_percentage=10'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.fast_shutdown=1'; \
} > /usr/local/etc/php/conf.d/opcache.ini

RUN { \
    echo 'upload_max_filesize=64M'; \
    echo 'post_max_size=64M'; \
    echo 'max_execution_time=300'; \
    echo 'memory_limit=256M'; \
    echo 'max_input_vars=3000'; \
} > /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html

USER www-data
