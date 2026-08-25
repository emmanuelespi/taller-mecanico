FROM php:8.2-fpm

# 1. Instalar dependencias del sistema y extensiones necesarias para Laravel y Livewire
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev libzip-dev zip unzip nginx \
    libpq-dev nodejs npm \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip

# 2. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Directorio de trabajo
WORKDIR /var/www

# 4. Copiar código del proyecto
COPY . .

# 5. Instalar paquetes de PHP y JS (Vite / Livewire)
RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN npm run build

# 6. Permisos de carpetas en Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# 7. Configuración rápida de Nginx para el puerto de Render
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

# 8. Script de inicio: ejecuta migraciones y levanta PHP-FPM + Nginx
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    php-fpm -D && \
    nginx -g 'daemon off;'
