# Usar una imagen base de PHP
FROM php:8.0-fpm

# Instalar extensiones de PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configurar la ubicación de trabajo en el contenedor
WORKDIR /var/www/html

# Copiar el código de tu aplicación Laravel al contenedor
COPY . .

# Instalar las dependencias de Composer
RUN composer install

# Generar la clave de aplicación de Laravel
RUN php artisan key:generate

# Exponer el puerto 9000 (puerto por defecto de PHP-FPM)
EXPOSE 9000

# Iniciar el servidor PHP-FPM
CMD ["php-fpm"]
