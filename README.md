# Taller Mecánico

Sistema de gestión para un taller mecánico: clientes, vehículos, órdenes de trabajo,
servicios, repuestos e inventarios. Proyecto construido con PHP (Laravel) y Vite.

## Características principales

- Gestión de clientes y vehículos.
- Creación y seguimiento de órdenes de trabajo (Work Orders).
- Registro de servicios y repuestos por orden.
- Control de movimientos de inventario.
- Autenticación de usuarios y roles básicos.

## Requisitos

- PHP 8.1+ con extensiones comunes
- Composer
- Node.js 16+ y npm
- MySQL / MariaDB (o otra base de datos soportada por Laravel)
- Git

## Instalación rápida (desarrollo)

1. Clona el repositorio:

```bash
git clone <repo-url> taller-mecanico
cd taller-mecanico
```

2. Instala dependencias PHP y JavaScript:

```bash
composer install
npm install
```

3. Copia el archivo de entorno y genera la clave de la aplicación:

```bash
copy .env.example .env    # Windows
php artisan key:generate
```

4. Configura la conexión a la base de datos en `.env` (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD).

5. Ejecuta migraciones y seeders (si aplica):

```bash
php artisan migrate --seed
```

6. Inicia el servidor de desarrollo y el watcher de assets (Vite):

```bash
npm run dev       # Vite (assets)
php artisan serve  # servidor Laravel (http://127.0.0.1:8000)
```

> Nota: en este workspace el task de Vite (`npm run dev`) puede ya estar corriendo.

## Ejecutar pruebas

```bash
php artisan test
# o
vendor\bin\phpunit
```

## Estructura relevante del proyecto

- `app/` — lógica de la aplicación, modelos y controladores.
- `resources/views/` — vistas Blade.
- `resources/js/` y `resources/css/` — assets front-end, integrados con Vite.
- `database/migrations/` — migraciones de base de datos.
- `routes/web.php` — rutas web principales.

## Uso básico

- Accede al panel en `http://127.0.0.1:8000` después de arrancar el servidor.
- Registra o usa datos de seeders para probar flujos (clientes, vehículos, órdenes).

## Contribuir

1. Crea una rama feature: `git checkout -b feature/nombre-descriptivo`
2. Haz commits claros y atómicos.
3. Abre un pull request describiendo los cambios.

Por favor abre issues para bug reports o solicitudes de features.

## Licencia

Licencia: MIT (ajustar según corresponda)

## Créditos

Proyecto inicial y scaffolding basado en Laravel + Vite. Mantén este archivo actualizado con instrucciones específicas del despliegue en producción si se requiere.
