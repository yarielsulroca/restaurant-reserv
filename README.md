<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# API de Gestión de Restaurante

API RESTful desarrollada con Laravel para la gestión de reservas y mesas de restaurante.

## Características

- Gestión de mesas (CRUD completo)
- Gestión de reservas (CRUD completo)
- Autenticación con Laravel Sanctum
- Documentación con Swagger/OpenAPI
- Respuestas JSON estandarizadas
- Validación de datos
- Paginación de resultados

## Requisitos

- PHP >= 8.1
- Composer
- MySQL/MariaDB
- Laravel 10.x

## Instalación

1. Clonar el repositorio:
```bash
git clone https://github.com/tuusuario/restaurant.git
cd restaurant
2. Instalar las dependencias:
```bash
composer install
```
3. Crear la base de datos y configurar las variables de entorno:
```bash
cp .env.example .env
```
4. Generar la clave de aplicación:
```bash
php artisan key:generate
```
5. Migrar la base de datos:
```bash
php artisan migrate
```
6. Iniciar el servidor de desarrollo:
```bash
php artisan serve
```
7. Acceder a la documentación de la API en http://localhost:8000/api-docs

## Contribuciones

Si deseas contribuir al proyecto, puedes hacerlo escribiendo a sulroca@gmail.com, o abriendo un pull request en el repositorio de GitHub.

## Licencia

El proyecto se encuentra bajo la licencia MIT. Puedes encontrar el archivo LICENSE en la raíz del proyecto.