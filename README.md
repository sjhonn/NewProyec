# Acme Services

Sistema web de gestión comercial para cuentas, contactos, oportunidades, compromisos y actividades. El backend utiliza PHP 8, PostgreSQL y PDO; la interfaz utiliza Bootstrap 4.6.2 y Font Awesome.

## Requisitos

- Cuenta gratuita de Supabase
- Cuenta gratuita de Vercel
- PHP 8.2 o superior para desarrollo local
- Composer 2

## Base de datos

1. Crea un proyecto en Supabase.
2. Abre el editor SQL y ejecuta `database/schema.sql`.
3. Opcionalmente, ejecuta `database/seed.sql`.
4. Obtén la conexión Transaction Pooler de Supabase, con puerto `6543`.

## Variables de entorno

Copia `.env.example` como `.env` para desarrollo local. En Vercel agrega las mismas variables desde Project Settings:

| Variable | Uso |
| --- | --- |
| `DATABASE_URL` | URL PostgreSQL de Supabase |
| `APP_ENV` | `production` en Vercel |
| `APP_KEY` | Cadena aleatoria de al menos 32 caracteres |

Ejemplo de conexión:

```text
postgresql://postgres.PROJECT_REF:PASSWORD@aws-0-REGION.pooler.supabase.com:6543/postgres?sslmode=require
```

## Desarrollo local

```bash
composer install
php -S localhost:8080 router.php
```

Abre `http://localhost:8080`.

## Despliegue en Vercel

1. Importa el repositorio en Vercel.
2. No selecciones un framework.
3. Configura las tres variables de entorno.
4. Despliega el proyecto.

`vercel.json` utiliza el runtime comunitario `vercel-php@0.9.0` y dirige las solicitudes dinámicas a `api/index.php`.

## Estructura

```text
api/                Entrada serverless
database/           Esquema y datos iniciales
public/assets/      Estilos y scripts
src/                Aplicación, seguridad y persistencia
views/              Vistas PHP
vercel.json         Configuración de Vercel
```
