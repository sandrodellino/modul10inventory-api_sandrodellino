FROM php:8.3-apache 
 RUN apt-get update && apt-get install -y --no-install-recommends \ 
     git unzip libpq-dev libonig-dev libxml2-dev \ 
     && docker-php-ext-install pdo_pgsql mbstring dom xml \ 
     && a2enmod rewrite && rm -rf /var/lib/apt/lists/* 
 COPY --from=composer:2 /usr/bin/composer /usr/bin/composer 
 WORKDIR /var/www/html 
 COPY . . 
 RUN composer install --no-dev --prefer-dist --no-interaction \ 
     --optimize-autoloader \ 
     && sed -i 's!/var/www/html!/var/www/html/public!g' \ 
        /etc/apache2/sites-available/000-default.conf \ 
     && chown -R www-data:www-data storage bootstrap/cache 
 EXPOSE 80 
 CMD ["apache2-foreground"] 