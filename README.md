# API de E-commerce Segura

API REST desarrollada con Laravel 12 para gestionar usuarios, productos y órdenes de compra de un e-commerce básico.

El proyecto incluye autenticación por tokens con Laravel Sanctum, CRUD de productos, creación de órdenes, control de inventario, historial de compras y documentación de endpoints mediante Swagger/OpenAPI.

## Tecnologías utilizadas

- Laravel 12
- PHP 8.2 o superior
- MySQL
- Laravel Sanctum
- Swagger / OpenAPI
- L5-Swagger
- Stripe PHP
- Composer

## Funcionalidades

### Autenticación

- Registro de usuarios
- Inicio de sesión
- Generación de tokens con Laravel Sanctum
- Consulta del usuario autenticado
- Cierre de sesión y eliminación del token actual

### Productos

- Listado público de productos
- Consulta de un producto por ID
- Creación de productos con autenticación
- Actualización de productos con autenticación
- Eliminación de productos con autenticación
- Validación de datos mediante Form Requests

### Órdenes

- Creación de órdenes de compra
- Validación de productos disponibles
- Validación de stock
- Cálculo del total desde el servidor
- Descuento automático del inventario
- Registro del detalle de cada producto
- Historial de compras del usuario autenticado
- Consulta individual de órdenes
- Restricción para evitar consultar órdenes de otros usuarios

### Pagos

Se instaló el paquete oficial de Stripe y se agregó la estructura inicial para procesar pagos asociados a una orden.

Actualmente el endpoint de pagos realiza las siguientes validaciones:

- Comprueba que la orden pertenezca al usuario autenticado.
- Verifica que la orden se encuentre en estado pendiente.
- Devuelve el monto, la moneda y el estado actual de la orden.

La creación y confirmación del PaymentIntent quedó pendiente debido al tiempo disponible para completar el proyecto.

Por esta razón, la aplicación no registra pagos ficticios ni cambia una orden al estado pagado.

## Requisitos

Antes de instalar el proyecto se necesita:

- PHP 8.2 o superior
- Composer
- MySQL
- Git

## Instalación

Clonar el repositorio:

```bash
git clone https://github.com/DutchMjolnir/laravel-ecommerce-api.git
```

Entrar en la carpeta:

```bash
cd laravel-ecommerce-api
```

Instalar las dependencias:

```bash
composer install
```

Crear el archivo de entorno:

```bash
cp .env.example .env
```

Generar la llave de la aplicación:

```bash
php artisan key:generate
```

## Configuración de la base de datos

Crear una base de datos en MySQL:

```sql
CREATE DATABASE laravel_ecommerce_api;
```

Configurar las siguientes variables en el archivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_ecommerce_api
DB_USERNAME=root
DB_PASSWORD=
```

El usuario y la contraseña pueden cambiar según la configuración local de MySQL.

## Migraciones y datos de ejemplo

Ejecutar las migraciones:

```bash
php artisan migrate
```

Ejecutar los seeders:

```bash
php artisan db:seed
```

También se pueden ejecutar ambos procesos desde cero con:

```bash
php artisan migrate:fresh --seed
```

El seeder agrega varios productos de ejemplo al catálogo.

## Configuración de Stripe

Agregar las credenciales de prueba en el archivo `.env`:

```env
STRIPE_KEY=pk_test_example
STRIPE_SECRET=sk_test_example
```

Las credenciales reales no deben agregarse al repositorio.

La integración completa del PaymentIntent todavía se encuentra pendiente.

## Generar la documentación de Swagger

Ejecutar:

```bash
php artisan l5-swagger:generate
```

Para evitar problemas al cargar los recursos de Swagger detrás de un proxy HTTPS o GitHub Codespaces, se utiliza:

```env
L5_SWAGGER_USE_ABSOLUTE_PATH=false
```

## Ejecutar el proyecto

Iniciar el servidor:

```bash
php artisan serve
```

El proyecto estará disponible normalmente en:

```text
http://127.0.0.1:8000
```

## Documentación de la API

Con el servidor activo, Swagger UI se encuentra disponible en:

```text
http://127.0.0.1:8000/api/documentation
```

Desde Swagger UI se pueden consultar los endpoints, parámetros, cuerpos JSON y respuestas principales de la API.

Para probar endpoints protegidos:

1. Registrar un usuario o iniciar sesión.
2. Copiar el token recibido.
3. Presionar el botón `Authorize` en Swagger.
4. Ingresar el token.
5. Ejecutar los endpoints protegidos.

## Endpoints principales

### Autenticación

```text
POST /api/register
POST /api/login
GET  /api/user
POST /api/logout
```

### Productos

```text
GET    /api/products
GET    /api/products/{product}
POST   /api/products
PUT    /api/products/{product}
PATCH  /api/products/{product}
DELETE /api/products/{product}
```

Los endpoints `GET` de productos son públicos. Las operaciones para crear, actualizar y eliminar requieren autenticación.

### Órdenes

```text
GET  /api/orders
POST /api/orders
GET  /api/orders/{order}
```

Ejemplo para crear una orden:

```json
{
    "items": [
        {
            "product_id": 1,
            "quantity": 2
        },
        {
            "product_id": 2,
            "quantity": 1
        }
    ]
}
```

Los precios y subtotales no se reciben desde el cliente. La aplicación obtiene el precio almacenado en la base de datos y calcula el total desde el backend.

### Pago parcial

```text
POST /api/orders/{order}/payment
```

Ejemplo del cuerpo:

```json
{
    "payment_method_id": "pm_test_pending"
}
```

Este endpoint se encuentra preparado y documentado, pero el procesamiento real mediante Stripe quedó pendiente.

## Formato general de respuestas

Respuesta exitosa:

```json
{
    "success": true,
    "message": "Operacion realizada correctamente.",
    "data": {}
}
```

Respuesta de error:

```json
{
    "success": false,
    "message": "No fue posible realizar la operacion."
}
```

Los errores de validación incluyen los campos que no cumplieron las reglas definidas.

## Estructura principal de la base de datos

El proyecto utiliza las siguientes tablas:

- `users`: usuarios registrados
- `products`: catálogo de productos
- `orders`: órdenes de compra
- `order_items`: detalle de productos por orden
- `payments`: estructura inicial para registrar pagos
- `personal_access_tokens`: tokens generados por Laravel Sanctum

## Consideraciones

- Las contraseñas se guardan utilizando el sistema de hash de Laravel.
- Los endpoints privados utilizan tokens de Laravel Sanctum.
- El cálculo de precios se realiza en el servidor.
- La creación de órdenes utiliza una transacción de base de datos.
- El stock se valida y actualiza durante la creación de la orden.
- Cada usuario solamente puede consultar sus propias órdenes.
- Las credenciales privadas deben configurarse únicamente en `.env`.
- El procesamiento real del pago con Stripe quedó pendiente por falta de tiempo.

## Autor

Luis Monge(Lucho, el calvo, fornido de 1.88 y 110KG de masa muscular)