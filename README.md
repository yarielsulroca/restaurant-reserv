<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# API de Gestión de Restaurante

API RESTful desarrollada con Laravel para la gestión integral de reservas y mesas de restaurante.

## Características Principales

### 1. Gestión de Mesas
- CRUD completo de mesas
- Zonas configurables (A, B, C)
- Estados de mesa: disponible, ocupada, reservada, mantenimiento
- Capacidad configurable por mesa
- Unión de mesas (hasta 3 mesas)
- Ubicación automática por zonas

### 2. Sistema de Reservas
- Horarios específicos por día:
  - Lunes a Viernes: 10:00 a 24:00
  - Sábado: 22:00 a 02:00 (día siguiente)
  - Domingo: 12:00 a 16:00
- Duración predeterminada: 1:45 horas
- Tiempo de preparación: 15 minutos
- Validaciones automáticas:
  - Conflictos de horarios
  - Disponibilidad de mesas
  - Capacidad suficiente
  - Tiempo mínimo de anticipación (15 minutos)

### 3. Características Técnicas
- Autenticación con Laravel Sanctum
- Cache en memoria para optimización
- Documentación OpenAPI/Swagger
- Auditoría de cambios
- Sistema de roles y permisos
- Notificaciones en tiempo real
- Paginación y filtros avanzados

## Estructura del Proyecto

```
restaurant/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   └── V1/           # Controladores API V1
│   │   ├── Requests/             # Validación de requests
│   │   └── Resources/            # Transformadores de respuesta
│   ├── Models/                   # Modelos Eloquent
│   ├── Services/                 # Lógica de negocio
│   └── Documentation/            # Documentación OpenAPI
├── database/
│   ├── migrations/               # Migraciones DB
│   └── seeders/                  # Datos de prueba
├── routes/
│   └── api.php                   # Rutas API
└── tests/                        # Pruebas automatizadas
```

## Requisitos Técnicos

- PHP >= 8.1
- MySQL/MariaDB
- Composer
- Laravel 10.x

## Configuración del Entorno

1. Clonar el repositorio:
```bash
git clone https://github.com/tuusuario/restaurant.git
cd restaurant
```

2. Instalar dependencias:
```bash
composer install
```

3. Configurar el entorno:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configurar la base de datos en `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restaurant
DB_USERNAME=root
DB_PASSWORD=
```

5. Migrar la base de datos:
```bash
php artisan migrate --seed
```

## Documentación API

La documentación completa de la API está disponible en:
- Desarrollo: http://localhost:8000/api/documentation
- Producción: https://tudominio.com/api/documentation

### Endpoints Principales

#### Mesas
- GET /api/v1/mesas - Listar mesas
- POST /api/v1/mesas - Crear mesa
- GET /api/v1/mesas/{id} - Ver mesa
- PUT /api/v1/mesas/{id} - Actualizar mesa
- DELETE /api/v1/mesas/{id} - Eliminar mesa

#### Reservas
- GET /api/v1/reservas - Listar reservas
- POST /api/v1/reservas - Crear reserva
- GET /api/v1/reservas/{id} - Ver reserva
- PUT /api/v1/reservas/{id} - Actualizar reserva
- DELETE /api/v1/reservas/{id} - Cancelar reserva

## Seguridad

- Autenticación mediante tokens (Sanctum)
- Roles y permisos granulares
- Validación de datos en cada request
- Protección CSRF
- Rate limiting

## Contribuciones

Para contribuir al proyecto:
1. Fork el repositorio
2. Crea una rama para tu feature
3. Envía un pull request

O contacta directamente a: sulroca@gmail.com

## Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo LICENSE para más detalles.