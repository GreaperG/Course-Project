FROM php:8.4-fpm
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    default-mysql-client \
    nodejs \
    npm \
    && docker-php-ext-install intl pdo pdo_mysql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install --optimize-autoloader --no-scripts

COPY package.json package-lock.json ./

COPY . .

RUN php bin/console importmap:install --env=prod

RUN php bin/console asset-map:compile --env=prod

RUN npm install

RUN npm run build

RUN ls -la public/
RUN ls -la public/assets/
RUN ls -la public/build/


RUN mkdir -p var/cache var/log && chown -R www-data:www-data var/
RUN php bin/console cache:clear --env=prod --no-warmup
RUN php bin/console cache:warmup --env=prod

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]

