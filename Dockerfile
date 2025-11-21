FROM php:8.2-apache
RUN apt-get update && apt-get install -y unzip git && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY . /var/www/html/
RUN composer install
RUN chown -R www-data:www-data /var/www/html/
EXPOSE 80
CMD ["apache2-foreground"]