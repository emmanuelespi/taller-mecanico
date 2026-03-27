# AGENT.md - Sistema de Gestión de Taller Mecánico (Livewire + Laravel)

## 1. Propósito del Proyecto
Sistema integral para la gestión de talleres mecánicos construido con Laravel y Livewire. Permite administrar órdenes de trabajo, clientes, vehículos, servicios (afinaciones, cambios específicos), inventario de insumos (aceites, filtros, bujías) y usuarios con roles diferenciados. Desarrollado como proyecto de portafolio siguiendo una arquitectura basada en **Services** para mantener la lógica de negocio desacoplada y componentes Livewire reutilizables.

## 2. Stack Tecnológico
- **Backend Framework:** Laravel 11
- **Frontend Reactivo:** Livewire 3.x (con Alpine.js integrado)
- **UI/Estilos:** TailwindCSS
- **Base de Datos:** MySQL / PostgreSQL
- **Autenticación:** Laravel Breeze / Jetstream (con roles personalizados)
- **JavaScript:** Alpine.js (interacciones puntuales)
- **Arquitectura:** Service Layer Pattern
- **Reportes:** Laravel DomPDF (planificado)

## 3. Arquitectura del Proyecto (Service Layer Pattern)

### Estructura de Carpetas
app/
├── Http/
│ ├── Livewire/
│ │ ├── Dashboard/
│ │ │ └── AdminDashboard.php
│ │ ├── Orders/
│ │ │ ├── OrderList.php
│ │ │ ├── CreateOrder.php
│ │ │ └── EditOrder.php
│ │ ├── Customers/
│ │ │ ├── CustomerList.php
│ │ │ └── CreateCustomer.php
│ │ ├── Vehicles/
│ │ │ ├── VehicleIndex.php (componente contenedor)
│ │ │ └── VehicleList.php (listado)
│ │ ├── Services/
│ │ │ ├── ServiceIndex.php
│ │ │ └── ServiceList.php
│ │ ├── Inventory/
│ │ │ ├── ProductIndex.php
│ │ │ └── ProductList.php
│ │ └── Users/
│ │ ├── UserIndex.php
│ │ └── UserList.php
│ └── Controllers/
│ └── (Solo para APIs externas si es necesario)
│
├── Services/ # Capa de servicios (lógica de negocio)
│ ├── CustomerService.php
│ ├── VehicleService.php
│ ├── WorkOrderService.php
│ ├── ServiceCatalogService.php # Gestión de servicios (afinaciones, etc)
│ ├── InventoryService.php
│ └── UserService.php
│
├── Models/
│ ├── User.php
│ ├── Customer.php
│ ├── Vehicle.php
│ ├── WorkOrder.php
│ ├── Service.php # Catálogo de servicios (afinación mayor/menor)
│ ├── Product.php # Productos de inventario (aceites, filtros, bujías)
│ ├── OrderProduct.php # Pivote: productos usados en órdenes
│ └── OrderService.php # Pivote: servicios realizados en órdenes
│
├── View/
│ └── Components/ # Componentes Blade reutilizables
│ ├── forms/
│ │ ├── VehicleForm.blade.php # Componente reutilizable para vehículos
│ │ ├── CustomerForm.blade.php
│ │ └── ServiceForm.blade.php
│ ├── tables/
│ │ ├── VehicleTable.blade.php # Tabla reutilizable para vehículos
│ │ └── CustomerTable.blade.php
│ └── modals/
│ └── ConfirmModal.blade.php
│
└── ...

## 4. Entidades Principales (Modelos y Relaciones Detalladas)

### Customer (Cliente)
- `id`, `name`, `email`, `phone`, `address`, `document_type`, `document_number`, `created_at`
- **Relaciones:** `hasMany(Vehicle::class)`, `hasMany(WorkOrder::class)`

### Vehicle (Vehículo)
- `id`, `customer_id`, `brand`, `model`, `year`, `license_plate`, `vin`, `color`, `engine_type`, `transmission`, `notes`
- **Relaciones:** `belongsTo(Customer::class)`, `hasMany(WorkOrder::class)`
- **Reglas:** La placa es única en el sistema

### Service (Catálogo de Servicios)
- `id`, `name`, `category` (afinación_mayor, afinación_menor, cambio_específico, reparación), `description`, `estimated_hours`, `base_price`, `is_active`
- **Tipos de servicios predefinidos:**
  - **Afinación Mayor:** Cambio de correa de distribución, bujías, filtros, aceite, revisión de frenos, etc.
  - **Afinación Menor:** Cambio de aceite, filtro de aceite, filtro de aire, revisión de niveles
  - **Cambios Específicos:** Cambio de frenos, embrague, batería, neumáticos, etc.
- **Relaciones:** `belongsToMany(WorkOrder::class)->withPivot('quantity', 'price', 'discount')`

### Product (Producto/Insumo)
- `id`, `name`, `sku`, `category` (aceites, filtros, bujías, frenos, refrigerantes, etc.), `description`, `stock`, `min_stock`, `unit` (litro, unidad, juego), `purchase_price`, `sale_price`, `supplier_id`, `location` (ubicación en taller)
- **Relaciones:** `belongsToMany(WorkOrder::class)->withPivot('quantity', 'price')`, `belongsTo(Supplier::class)`

### WorkOrder (Orden de Trabajo)
- `id`, `order_number`, `vehicle_id`, `customer_id`, `user_id` (mecánico asignado), `created_by` (recepcionista/admin), `entry_date`, `diagnosis`, `status` (pending, in_progress, completed, delivered, cancelled), `total_services`, `total_products`, `total_cost`, `payment_status` (pending, paid, partial), `payment_method`, `notes`, `completed_at`, `delivered_at`
- **Relaciones:** `belongsTo(Vehicle::class)`, `belongsTo(Customer::class)`, `belongsTo(User::class)`, `belongsToMany(Service::class)->withPivot('price', 'discount')`, `belongsToMany(Product::class)->withPivot('quantity', 'price')`

### User (Usuario del Sistema)
- Campos estándar Laravel + `role` (admin, receptionist, mechanic)
- **Roles y Permisos:**
  - **Admin:** Acceso total, reportes, configuración, gestión de usuarios
  - **Recepcionista:** Crear clientes, vehículos, órdenes, gestionar citas, cobros
  - **Mecánico:** Ver órdenes asignadas, actualizar diagnóstico, marcar servicios completados

## 5. Módulos Completos del Sistema

### 1. Dashboard de Administrador ✅
- Componente: `AdminDashboard.php`
- **Métricas en tiempo real:**
  - Órdenes activas (pending + in_progress)
  - Total clientes registrados
  - Ingresos del mes (órdenes completadas pagadas)
  - Productos bajo stock (stock < min_stock)
- **Listado:** Últimas 10 órdenes de trabajo
- **Alertas:** Productos por debajo del stock mínimo

### 2. Gestión de Clientes ✅
- Componentes Livewire:
  - `CustomerList.php` - Listado con búsqueda y filtros
  - `CreateCustomer.php` - Formulario de creación/edición
- **Funcionalidades:**
  - CRUD completo de clientes
  - Vista detallada con historial de vehículos y órdenes
  - Búsqueda por nombre, email, teléfono o documento
  - Componente reutilizable `CustomerForm.blade.php`

### 3. Gestión de Vehículos ✅
- Componentes Livewire:
  - `VehicleIndex.php` - Componente contenedor
  - `VehicleList.php` - Listado con filtros por cliente, marca, placa
- **Funcionalidades:**
  - CRUD completo de vehículos asociados a clientes
  - Búsqueda por placa (rápida para recepción)
  - Vista de historial de servicios por vehículo
  - Componentes reutilizables:
    - `VehicleForm.blade.php` - Formulario estandarizado
    - `VehicleTable.blade.php` - Tabla con acciones
- **Reglas de negocio:**
  - Un vehículo solo puede tener una orden activa a la vez
  - La placa es única en el sistema

### 4. Catálogo de Servicios ✅
- Componentes Livewire:
  - `ServiceIndex.php` - Gestión del catálogo
  - `ServiceList.php` - Listado por categorías
- **Tipos de servicios:**
  - **Afinación Mayor:** Servicios completos (cada 60,000-100,000 km)
  - **Afinación Menor:** Servicios rutinarios (cada 5,000-10,000 km)
  - **Cambios Específicos:** Reparaciones puntuales
  - **Reparaciones:** Trabajos no estandarizados
- **Funcionalidades:**
  - CRUD de servicios con precios base
  - Tiempo estimado por servicio
  - Activar/desactivar servicios según disponibilidad

### 5. Inventario de Productos ✅
- Componentes Livewire:
  - `ProductIndex.php` - Gestión del inventario
  - `ProductList.php` - Listado con control de stock
- **Categorías de productos:**
  - Aceites (motor, transmisión, dirección)
  - Filtros (aceite, aire, combustible, habitáculo)
  - Bujías (iridio, platino, cobre)
  - Frenos (pastillas, discos, líquido)
  - Refrigerantes
  - Correas y bandas
  - Iluminación (faros, bombillas)
  - Neumáticos
- **Funcionalidades:**
  - CRUD de productos
  - Control de stock con alertas de mínimo
  - Movimientos de inventario (entradas/salidas)
  - Precio de compra y venta

### 6. Órdenes de Trabajo (En Desarrollo)
- **Características planificadas:**
  - Creación con wizard o formulario completo
  - Selección de cliente y vehículo (búsqueda Livewire)
  - Agregar servicios del catálogo (con precios dinámicos)
  - Agregar productos del inventario (con descuento automático de stock)
  - Cálculo automático de totales
  - Historial de estados y cambios
  - Impresión de orden/factura

### 7. Gestión de Usuarios (En Desarrollo)
- Componentes Livewire:
  - `UserIndex.php` - Gestión de usuarios
  - `UserList.php` - Listado con roles
- **Funcionalidades:**
  - CRUD de usuarios
  - Asignación de roles (admin, receptionist, mechanic)
  - Control de acceso por vistas y acciones
  - Auditoría de acciones (quién creó/ modificó órdenes)

## 6. Service Layer - Lógica de Negocio

Cada servicio encapsula la lógica compleja y reglas de negocio:

```php
// Ejemplo de estructura de servicios
app/Services/
├── CustomerService.php
│   ├── getAllCustomers()
│   ├── getCustomerWithVehicles($id)
│   ├── createCustomer(array $data)
│   ├── updateCustomer($id, array $data)
│   └── deleteCustomer($id) // Verifica si tiene órdenes
│
├── VehicleService.php
│   ├── getVehiclesByCustomer($customerId)
│   ├── getVehicleByPlate($plate)
│   ├── createVehicle(array $data)
│   ├── updateVehicle($id, array $data)
│   └── hasActiveWorkOrder($vehicleId)
│
├── ServiceCatalogService.php
│   ├── getServicesByCategory($category)
│   ├── getActiveServices()
│   ├── calculateServicePrice($serviceId, $discount = 0)
│   └── updateServicePrice($serviceId, $newPrice)
│
├── InventoryService.php
│   ├── getLowStockProducts()
│   ├── updateStock($productId, $quantity, $operation) // add/subtract
│   ├── checkStockAvailability($productId, $quantity)
│   └── getProductMovements($productId)
│
├── WorkOrderService.php
│   ├── createWorkOrder(array $data)
│   ├── addServiceToOrder($orderId, $serviceId, $price)
│   ├── addProductToOrder($orderId, $productId, $quantity)
│   ├── calculateTotal($orderId)
│   ├── changeStatus($orderId, $newStatus)
│   └── generateOrderNumber() // OT-2024-0001
│
└── UserService.php
    ├── getAllUsersByRole($role)
    ├── hasPermission($user, $action)
    └── getAssignedMechanics()
