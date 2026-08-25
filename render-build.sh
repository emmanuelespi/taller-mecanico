#!/usr/bin/env bash
# exit on error
set -o errexit

# 1. Instalar dependencias de PHP
composer install --no-dev --optimize-autoloader

# 2. Instalar dependencias de JS y compilar estilos/Livewire (Vite)
npm install
npm run build

# 3. Limpiar y generar caché de Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Crear enlace simbólico para imágenes/archivos públicos
php artisan storage:link || true

# 5. Ejecutar migraciones de base de datos
php artisan migrate --force
