# Taller Mecánico - Sistema Full-Stack y App Móvil

Sistema integral de gestión para talleres mecánicos: administración de clientes, vehículos, órdenes de trabajo, servicios, repuestos e inventarios. Desarrollado con una arquitectura desacoplada que incluye un backend en **Laravel 11**, una interfaz web interactiva basada en **Livewire**, y una **API RESTful** para su consumo mediante una **aplicación móvil nativa/multiplataforma (Expo / React Native)**.

## Características principales

- **Gestión completa del taller:** Control de clientes, vehículos, catálogo de servicios, inventario de repuestos y órdenes de trabajo en tiempo real.
- **API RESTful robusta:** Backend desacoplado con endpoints protegidos mediante **Laravel Sanctum** para la sincronización fluida de la aplicación móvil.
- **Autenticación y Roles:** Control de acceso seguro según roles (`admin`, `recepcionista`, `mecanico`) con middlewares personalizados para la protección de vistas y acciones.
- **Arquitectura de Software Limpia:** Implementación del patrón **Service Layer** para desacoplar la lógica de negocio de los controladores y componentes.
- **Despliegue Continuo (DevOps):** Contenerización con **Docker** e integración de scripts de construcción optimizados para despliegues rápidos en la nube (**Render**).

## Tecnologías Utilizadas

- **Backend Framework:** Laravel 11.x (PHP 8.2+)
- **Frontend Reactivo:** Livewire 3.x (con Alpine.js integrado) y TailwindCSS para estilos.
- **API y Autenticación:** Laravel Sanctum (Tokens de API)
- **Base de Datos:** MySQL / PostgreSQL
- **DevOps & Infraestructura:** Docker, Nginx, PHP-FPM, Render (CI/CD)
- **Pruebas y Validación:** Bruno / Postman

---

## Estructura de Endpoints de la API (Novedad)

El backend expone una serie de endpoints protegidos para la aplicación móvil:

### Autenticación
| Método | Ruta | Descripción |
| :--- | :--- | :--- |
| `POST` | `/api/login` | Iniciar sesión y obtener token Sanctum |
| `POST` | `/api/logout` | Cerrar sesión (requiere autenticación) |
| `GET` | `/api/me` | Obtener datos del usuario autenticado |

### Órdenes de Trabajo (`/api/work-orders`)
| Método | Ruta | Descripción |
| :--- | :--- | :--- |
| `GET` | `/api/work-orders` | Listado de órdenes con filtros de búsqueda y estado |
| `POST` | `/api/work-orders` | Crear nueva orden de trabajo |
| `GET` | `/api/work-orders/stats` | Obtener métricas y estadísticas globales de órdenes |
| `GET` | `/api/work-orders/{id}` | Consultar detalle de una orden específica |
| `PATCH` | `/api/work-orders/{order}/status` | Actualizar el estado de una orden |
| `POST` | `/api/work-orders/{order}/spare-parts` | Añadir repuesto/refacción a la orden |
| `DELETE` | `/api/work-orders/{order}/spare-parts/{partId}` | Remover repuesto de la orden |
| `POST` | `/api/work-orders/{order}/services` | Añadir servicio del catálogo a la orden |
| `DELETE` | `/api/work-orders/{order}/services/{serviceId}` | Remover servicio de la orden |

### Vehículos (`/api/vehicles`)
| Método | Ruta | Descripción |
| :--- | :--- | :--- |
| `GET` | `/api/vehicles` | Listado general de vehículos |
| `POST` | `/api/vehicles` | Registrar nuevo vehículo |
| `GET` | `/api/vehicles/{id}` | Ver detalles / Actualizar datos del vehículo |
| `GET` | `/api/vehicles/plate/{plate}` | Buscar vehículo por número de placa |
| `GET` | `/api/vehicles/client/{clientId}` | Consultar vehículos asignados a un cliente específico |

---

## Requisitos del Entorno

- **PHP** 8.2+ con extensiones comunes (`pdo`, `mbstring`, `zip`, `gd`, etc.)
- **Composer**
- **Node.js** 16+ y **npm**
- **Base de datos** MySQL, PostgreSQL o SQLite configurada
- **Git**

---

## Instalación rápida (Desarrollo Local)

1. **Clonar el repositorio:**
   ```bash
   git clone <repo-url> taller-mecanico
   cd taller-mecanico
   ```

2. **Instalar dependencias del Backend:**
   ```bash
   composer install
   ```

3. **Instalar dependencias del Frontend y compilar assets:**
   ```bash
   npm install
   ```

4. **Configurar el entorno:**
   Copia el archivo de variables de entorno de ejemplo y configúralo con tus credenciales:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Correr las migraciones y Seeders:**
   Puebla la base de datos con información de prueba para clientes, vehículos, servicios y usuarios:
   ```bash
   php artisan migrate --seed
   ```

6. **Compilar recursos y levantar servidores:**
   En terminales separadas ejecuta:
   - Para el compilador de assets (Vite):
     ```bash
     npm run dev
     ```
   - Para el servidor web local de Laravel:
     ```bash
     php artisan serve
     ```

---

## Configuración y Despliegue en Producción (Render / Docker)

El proyecto está preparado para su despliegue contenerizado y en plataformas de nube:

- **Docker:** El archivo [`dockerfile`](file:///c:/laragon/www/taller-mecanico/dockerfile) ejecuta Nginx y PHP-FPM conjuntamente sobre el puerto `10000`, facilitando la portabilidad del entorno de producción.
- **Render Script:** Se incluye el script [`render-build.sh`](file:///c:/laragon/www/taller-mecanico/render-build.sh) que automatiza las tareas de construcción para el servicio de hosting en Render:
  1. Descarga de dependencias con Composer sin entorno de desarrollo.
  2. Compilación de vistas e instalación de assets JS/Tailwind a través de Vite.
  3. Cacheo de rutas, configuraciones y vistas de Laravel para optimizar el rendimiento.
  4. Ejecución forzada de migraciones de base de datos en el entorno remoto.
