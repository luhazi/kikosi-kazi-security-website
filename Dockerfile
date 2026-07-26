FROM php:8.4-cli

WORKDIR /var/www

# System libraries + PHP extensions Laravel needs (mbstring, gd, zip, bcmath,
# exif, pdo_sqlite). Building these here is what most failed Render deploys miss.
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        curl \
        unzip \
        zip \
        sqlite3 \
        libzip-dev \
        libonig-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libxml2-dev \
        libsqlite3-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_sqlite zip mbstring gd bcmath exif \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP dependencies first for layer caching.
# --no-scripts prevents `artisan package:discover` from running before the app
# environment exists (a common build-time failure); it runs at container start.
COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader

# Copy the application source and rebuild the optimised autoloader (no scripts).
COPY . .
RUN composer dump-autoload --no-scripts --optimize

# Writable directories + executable start script.
RUN chmod -R 775 storage bootstrap/cache \
    && chmod +x docker/start.sh

# Render injects $PORT; the start script binds to it (defaults to 10000).
EXPOSE 10000

CMD ["sh", "docker/start.sh"]
