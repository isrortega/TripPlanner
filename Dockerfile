# Use the PHP fpm oficial image
FROM php:8.2-fpm

# Install required dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    zlib1g-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/symfony
COPY . /var/www/symfony

# Copy and fix entrypoint script permissions
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# sets project permissions
RUN chown -R www-data:www-data /var/www/symfony
USER www-data

# Sets the script as the entrypoint
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
EXPOSE 9000

CMD ["php-fpm"]
