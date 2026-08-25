FROM php:8.2-fpm

# 1. Dependencias del sistema
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev libzip-dev zip unzip nginx \
    libpq-dev nodejs npm \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip

# 2. Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# 3. Instalación de paquetes
RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

# 4. Crear archivo sqlite si no existe y dar permisos absolutos 777 a todo database y storage
RUN touch database/database.sqlite || true
RUN chown -R www-data:www-data /var/www
RUN chmod -R 777 /var/www/storage /var/www/bootstrap/cache /var/www/database

# 5. Configuración Nginx
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


# Script de inicio corregido
CMD php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear && \
    php artisan cache:clear || true && \
    php artisan migrate --force && \
    php artisan db:seed --force && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php-fpm -D && \
    nginx -g 'daemon off;'
