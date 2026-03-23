# 🫓 Empanadas POS — Sistema de Ventas

Sistema web de punto de venta para empanadas y papas rellenas, desarrollado en **Laravel 10**.

## 📋 Requisitos

- PHP >= 8.1
- Composer
- MySQL 5.7+ / MariaDB 10.3+
- Node.js (opcional, para assets)

---

## 🚀 Instalación en GitHub Codespaces

### 1. Clonar e instalar dependencias

```bash
cd /workspaces
git clone <tu-repositorio> empanadas-pos
cd empanadas-pos
composer install
```

### 2. Configurar entorno

```bash
cp .env.example .env
php artisan key:generate
```

Editar `.env` con los datos de tu base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=empanadas_pos
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Opción A — Usar el SQL directo (recomendado para entrega)

```bash
# Crear la base de datos e importar todo
mysql -u root -p < database/empanadas_pos.sql
```

### 4. Opción B — Usar migraciones y seeders de Laravel

```bash
# Crear la base de datos primero
mysql -u root -p -e "CREATE DATABASE empanadas_pos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Correr migraciones + seeders
php artisan migrate --seed
```

### 5. Levantar el servidor

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

En Codespaces el puerto 8000 quedará expuesto automáticamente.

---

## 🌐 Rutas del Sistema

| Ruta | Descripción |
|------|-------------|
| `/pos` | Punto de venta (vendedor) |
| `/admin` | Panel de administración |
| `/admin/products` | Gestión de productos |
| `/admin/clients` | Gestión de clientes |
| `/admin/reports` | Informes de ventas |

---

## 📦 Estructura del Proyecto

```
empanadas-pos/
├── app/
│   ├── Http/Controllers/
│   │   ├── PosController.php           ← Lógica del POS
│   │   └── Admin/
│   │       ├── ProductController.php   ← CRUD Productos
│   │       ├── ClientController.php    ← CRUD Clientes
│   │       └── ReportController.php    ← Informes
│   └── Models/
│       ├── Client.php
│       ├── Product.php
│       ├── Sale.php
│       └── SaleItem.php
├── database/
│   ├── migrations/                     ← Tablas de la BD
│   ├── seeders/DatabaseSeeder.php      ← Datos iniciales
│   └── empanadas_pos.sql               ← SQL completo listo
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php               ← Layout principal
│   │   └── admin.blade.php             ← Layout admin con sidebar
│   ├── pos/
│   │   ├── index.blade.php             ← Interfaz POS
│   │   └── receipt.blade.php           ← Recibo de venta
│   └── admin/
│       ├── products/                   ← Vistas CRUD productos
│       ├── clients/                    ← Vistas CRUD clientes
│       └── reports/index.blade.php     ← Dashboard de informes
└── routes/web.php                      ← Rutas
```

---

## 🎯 Casos de Uso Implementados

### Venta a Cliente de Mostrador
1. Ir a `/pos`
2. Clic en los productos deseados (se agregan al carrito)
3. El cliente por defecto es "Cliente de Mostrador"
4. Seleccionar método de pago
5. Clic en **Registrar Venta**

### Venta a Cliente Ya Creado
1. En el POS, clic en el botón de cambio de cliente (icono ↔)
2. Ir a pestaña **Buscar Cliente**
3. Escribir nombre o número de documento
4. Seleccionar el cliente de la lista
5. Proceder con la venta normalmente

### Venta a Cliente Nuevo
1. En el POS, clic en el botón de cambio de cliente
2. Ir a pestaña **Nuevo Cliente**
3. Ingresar los datos del cliente
4. Clic en **Guardar y Seleccionar**
5. El cliente queda seleccionado automáticamente para la venta

---

## 🗄️ Base de Datos Remota (para entrega)

Para que el profesor pueda acceder remotamente, ejecutar en MySQL:

```sql
-- Crear usuario con acceso remoto
CREATE USER 'empanadas_user'@'%' IDENTIFIED BY 'EmpanadaPOS2024!';
GRANT ALL PRIVILEGES ON empanadas_pos.* TO 'empanadas_user'@'%';
FLUSH PRIVILEGES;
```

Luego en el `.env`:
```env
DB_HOST=<IP_DEL_SERVIDOR>
DB_DATABASE=empanadas_pos
DB_USERNAME=empanadas_user
DB_PASSWORD=EmpanadaPOS2024!
```

---

## 🎨 Diseño

- **Tema**: Oscuro con rojo como color principal
- **Fuentes**: Bebas Neue (títulos) + Nunito (texto)
- **Framework CSS**: Custom (sin Bootstrap), usando CSS variables
- **Gráficos**: Chart.js 4.x
- **Íconos**: Font Awesome 6
