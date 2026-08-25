FROM php:8.2-fpm

# 1. Instalar dependencias
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev libzip-dev zip unzip nginx \
    libpq-dev nodejs npm \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip

# 2. Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# 3. Dependencias de PHP y JS
RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

# 4. Asignar permisos correctos al usuario del servidor web
RUN chown -R www-data:www-data /var/www
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# 5. Configuración de Nginx en puerto 10000
RUN echo 'server { \
    listen 10000; \
    index index.php index.html; \
    root /var/www/public; \
    location / { \
    try_files $uri $uri/ /index.php?$query_string; \
    } \
    location ~ \.php$ { \
    fastcgi_pass 127.0.0.1:9000; \
    fastcgi_index index.php; \
    include fastcgi_params; \
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \
    } \
    }' > /etc/nginx/sites-available/default

EXPOSE 10000

# 6. Script de inicio
CMD php artisan storage:link || true && \
    php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    php-fpm -D && \
    nginx -g 'daemon off;'
