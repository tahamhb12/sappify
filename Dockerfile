FROM dunglas/frankenphp:latest-alpine

RUN install-php-extensions \
    pcntl \
    pdo_mysql \
    bcmath \
    zip \
    intl \
    exif

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /app
WORKDIR /app

RUN composer install

RUN chmod +x /app/entrypoint.sh

ENTRYPOINT ["sh","/app/entrypoint.sh"]
