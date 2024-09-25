#!/bin/sh
set -e

# If there are not 'vendor' runs the composer install
if [ ! -d "vendor" ]; then
    echo "Vendor folder is not present, running composer install..."
    composer install
fi

# Start PHP-FPM
php-fpm