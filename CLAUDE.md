# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 9 e-commerce application called "Betoge" that provides a multi-vendor marketplace platform with membership management, commission tracking, and payment gateway integration.

### Key Features
- Multi-vendor marketplace with company registration and management
- Membership plans and commission system
- Product catalog with categories, variants, and stock management
- Shopping cart and order processing
- Payment gateway integration (configured via `ConfiguracionPasarela` model)
- Role-based access control using Spatie Laravel Permission
- Excel import/export functionality
- PDF generation using DomPDF
- DataTables integration for data presentation

## Development Commands

### Artisan Commands
```bash
# Start development server
php artisan serve

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Generate application key
php artisan key:generate

# Run queue workers (if using queues)
php artisan queue:work

# Create symbolic link for storage
php artisan storage:link
```

### Composer Commands
```bash
# Install dependencies
composer install

# Update dependencies
composer update

# Run autoload dump
composer dump-autoload
```

### Frontend Build Commands
```bash
# Install NPM dependencies
npm install

# Development build with hot reloading
npm run dev

# Production build
npm run build
```

### Testing Commands
```bash
# Run all tests
php artisan test
# or
./vendor/bin/phpunit

# Run specific test suite
./vendor/bin/phpunit --testsuite=Feature
./vendor/bin/phpunit --testsuite=Unit

# Run tests with coverage
./vendor/bin/phpunit --coverage-html coverage
```

## Architecture & Key Components

### Models & Relationships
- **User**: Extends Authenticatable, uses HasRoles trait for permissions
- **Empresa**: Company/vendor model with one-to-one relationship with User
- **Producto**: Product model with categories, variants, images, and stock
- **Categoria**: Product categories with hierarchical structure
- **Membresia**: Membership plans with associated features
- **Compra**: Order model with items, payments, and shipping
- **StockProducto**: Inventory management per product/variant
- **Cliente**: Customer model linked to vendors (vendedor_id)

### Controllers Structure
- **Admin Controllers**: `DashboardAdminController`, `UsuariosController`
- **Business Logic**: `EmpresasController`, `ProductosController`, `ComprasController`
- **E-commerce**: `TiendaController`, `CatalogoController`, `ClientesController`
- **Integrations**: `WebhookController` for payment gateway callbacks
- **Utilities**: `ActualizacionPreciosController`, `SolicitudController`

### Frontend Architecture
- **Blade Templates**: Located in `resources/views/`
- **Layout System**: Main layout in `layouts/app.blade.php` with sidebar navigation
- **CSS Framework**: Bootstrap 5.3.7 with custom styles (primary framework)
- **JavaScript**: jQuery, DataTables, SweetAlert2 (primary stack)
- **Build Tool**: Vite with Laravel plugin
- **Note**: Vite, TailwindCSS, and Alpine.js are only used for authentication pages (login/register) - the main application uses Bootstrap + jQuery stack

### Authentication & Authorization
- Laravel Breeze for authentication scaffolding
- Spatie Laravel Permission for role-based access control
- Custom middleware for role verification
- User types: 'admin', 'vendedor' (vendor), 'empresa' (company)

### Database Architecture
- Standard Laravel migrations in `database/migrations/`
- Seeders for initial data setup
- Foreign key relationships between models
- Soft deletes where applicable

## Configuration Notes

### Environment Setup
- Copy `.env.example` to `.env` and configure database credentials
- Set `APP_KEY` using `php artisan key:generate`
- Configure mail settings for notifications
- Set up payment gateway credentials

### Database Configuration
- Supports MySQL (primary), PostgreSQL, SQLite
- Configure `DB_*` variables in `.env`
- Run migrations and seeders for initial setup

### Frontend Assets
- CSS/JS assets compiled via Vite (primarily for auth pages)
- Bootstrap and jQuery loaded via CDN (main application stack)
- Custom styles in `resources/css/app.css`
- Images stored in `resources/images/` and `public/images/`
- **Note**: Main application does not use Vite build process - assets are loaded directly via CDN

## Key Dependencies

### Backend (Composer)
- `laravel/framework ^9.19`: Core Laravel framework
- `spatie/laravel-permission ^6.20`: Role and permission management
- `livewire/livewire ^2.12`: Dynamic interfaces
- `barryvdh/laravel-dompdf ^3.1`: PDF generation
- `maatwebsite/excel ^3.1`: Excel import/export
- `yajra/laravel-datatables-oracle 10.0`: DataTables server-side processing

### Frontend (NPM)
- `vite ^4.0.0`: Build tool and dev server (only used for login/register pages)
- `tailwindcss ^3.4.17`: Utility-first CSS framework (only used for login/register pages)
- `alpinejs ^3.4.2`: Minimal JavaScript framework (only used for login/register pages)
- `bootstrap ^5.3.7`: CSS framework (primary frontend framework)
- `datatables.net ^2.3.2`: Interactive tables
- `sweetalert2 ^11.22.2`: Beautiful alerts

## Development Workflow

### Adding New Features
1. Create migration files for database changes
2. Generate/update models with relationships
3. Create controllers following RESTful conventions
4. Define routes in appropriate route files
5. Create Blade views with consistent layout
6. Add frontend interactions using Alpine.js/jQuery
7. Write feature tests for new functionality

### Code Conventions
- Follow PSR-12 coding standards
- Use Laravel's naming conventions (PascalCase for models, snake_case for tables)
- Implement proper error handling and validation
- Use Laravel's built-in features (Eloquent, Blade, etc.)
- Maintain consistent indentation (4 spaces)

### Database Migrations
- Always create reversible migrations
- Use descriptive migration names
- Add proper indexes for foreign keys
- Include default values where appropriate

### Custom Middleware
- **VerificarMembresia**: Validates membership status and restrictions
- **VerificarEmpresa**: Ensures user has associated company profile
- Used in route groups to control access based on business logic

### Route Structure
- **Public Routes**: Welcome page, contact forms, catalog browsing
- **Authenticated Routes**: Dashboard, profile management, business operations
- **Admin Routes**: User management, system administration
- **API Routes**: AJAX endpoints for cities, data processing
- **Webhook Routes**: Payment gateway callbacks and integrations

### Key Business Logic
- **Membership System**: Three-tier membership plans (Basic/Premium/Enterprise) with feature restrictions
- **Commission Tracking**: Automated commission calculation on sales with `Comision` model
- **Multi-vendor Architecture**: Each `Empresa` operates independently with own products/customers
- **Stock Management**: Real-time inventory tracking via `StockProducto` and `MovimientoStock`
- **Payment Integration**: Configurable payment gateways via `ConfiguracionPasarela`

## Sistema de Templates de Tienda

### Arquitectura

El sistema de templates utiliza el **Strategy Pattern** combinado con **Repository Pattern** para permitir múltiples diseños visuales de tienda sin modificar el código base. Cada template implementa la interfaz `TemplateStrategyInterface` y puede ser seleccionado por la empresa desde su configuración.

#### Componentes Principales

1. **TemplateTienda Model** (`app/Models/TemplateTienda.php`)
   - Almacena configuración de templates en base de datos
   - Tabla: `templates_tienda`
   - Campos: codigo, nombre, descripcion, vistas (index/categoria/producto), layout, preview_image, configuracion JSON

2. **TemplateStrategyInterface** (`app/Services/Templates/Contracts/TemplateStrategyInterface.php`)
   - Define contrato para todas las estrategias de templates
   - Métodos: getViewIndex(), getViewCategoria(), getViewProducto(), getLayout(), prepareData(), getAssets(), getConfig()

3. **AbstractTemplateStrategy** (`app/Services/Templates/AbstractTemplateStrategy.php`)
   - Clase base abstracta que implementa comportamiento común
   - Template Method Pattern para proveer implementación base
   - Propiedades: viewIndex, viewCategoria, viewProducto, layout, config

4. **Estrategias Concretas** (`app/Services/Templates/Strategies/`)
   - **DefaultTemplateStrategy**: Template clásico del sistema
   - **BrasiliaTemplateStrategy**: Template dinámico con carruseles y animaciones

5. **TemplateResolver** (`app/Services/Templates/TemplateResolver.php`)
   - Context del patrón Strategy
   - Resuelve qué strategy usar para cada empresa
   - Cache de 1 hora para optimizar performance
   - Mapeo dinámico de códigos a clases Strategy

6. **TemplateRepository** (`app/Repositories/TemplateRepository.php`)
   - Capa de abstracción para acceso a datos
   - Métodos: findByEmpresa(), getAllActivos(), getDefault(), findByCodigo(), getForSelector()

7. **TemplateServiceProvider** (`app/Providers/TemplateServiceProvider.php`)
   - Registra TemplateResolver como singleton
   - Registra TemplateRepository como bind

#### Patrones Aplicados

- **Strategy Pattern**: Diferentes algoritmos (templates) intercambiables en tiempo de ejecución
- **Repository Pattern**: Abstracción de acceso a datos del modelo TemplateTienda
- **Template Method**: AbstractTemplateStrategy provee estructura base para strategies
- **Dependency Injection**: Controllers reciben TemplateResolver vía constructor
- **Factory Pattern**: TemplateResolver crea instancias de strategies dinámicamente

#### Principios SOLID

- **S**ingle Responsibility: Cada strategy maneja solo su template específico
- **O**pen/Closed: Sistema abierto a extensión (nuevos templates) pero cerrado a modificación
- **L**iskov Substitution: Todas las strategies son intercambiables sin afectar funcionalidad
- **I**nterface Segregation: TemplateStrategyInterface es específica y cohesiva
- **D**ependency Inversion: Código depende de abstracciones (interface), no de concreciones

---

### Flujo de Funcionamiento

1. Usuario visita tienda: `http://localhost/empresa/{slug}`
2. TiendaController recibe request
3. TiendaController inyecta TemplateResolver en constructor
4. Se llama `resolveForEmpresa($empresa)` que:
   - Busca en cache (`template_strategy_empresa_{id}`)
   - Si no está en cache, obtiene `template_tienda_id` de empresa
   - Crea instancia de Strategy correspondiente
   - Cachea por 1 hora
5. Strategy retorna nombre de vista (ej: 'tienda.brasilia_index')
6. Strategy puede preparar/transformar datos con `prepareData()`
7. Controller renderiza vista con los datos preparados

---

### Cómo Agregar un Nuevo Template

#### Paso 1: Crear las Vistas Blade

Crear en `resources/views/tienda/`:

1. **Layout base**: `{nombre}_layout.blade.php`
   ```blade
   <!DOCTYPE html>
   <html>
   <head>
       @yield('title')
       <!-- CSS del template -->
       @stack('styles')
   </head>
   <body class="@yield('body-class')">
       @include('tienda.partials.{nombre}.header')

       <main>
           @yield('content')
       </main>

       @include('tienda.partials.{nombre}.footer')

       <!-- JS del template -->
       @stack('scripts')
   </body>
   </html>
   ```

2. **Vistas principales**:
   - `{nombre}_index.blade.php` - Página principal
   - `{nombre}_categoria.blade.php` - Listado de productos
   - `{nombre}_producto.blade.php` - Detalle de producto

3. **Partials** en `resources/views/tienda/partials/{nombre}/`:
   - `header.blade.php`
   - `footer.blade.php`
   - Otros componentes reutilizables

#### Paso 2: Crear la Strategy Class

Crear archivo: `app/Services/Templates/Strategies/{Nombre}TemplateStrategy.php`

```php
<?php

namespace App\Services\Templates\Strategies;

use App\Services\Templates\AbstractTemplateStrategy;

class MinimalTemplateStrategy extends AbstractTemplateStrategy
{
    protected string $viewIndex = 'tienda.minimal_index';
    protected string $viewCategoria = 'tienda.minimal_categoria';
    protected string $viewProducto = 'tienda.minimal_producto';
    protected string $layout = 'tienda.minimal_layout';

    public function getAssets(): array
    {
        return [
            'css' => [
                asset('css/minimal-theme.css'),
            ],
            'js' => [
                asset('js/minimal-theme.js'),
            ],
        ];
    }

    public function prepareData(array $data): array
    {
        // Transformaciones específicas del template
        $data['minimalMode'] = true;
        return $data;
    }
}
```

#### Paso 3: Registrar en TemplateResolver

Editar `app/Services/Templates/TemplateResolver.php`:

```php
private array $strategyMap = [
    'default' => DefaultTemplateStrategy::class,
    'brasilia' => BrasiliaTemplateStrategy::class,
    'minimal' => MinimalTemplateStrategy::class, // ← AGREGAR AQUÍ
];
```

#### Paso 4: Insertar en Base de Datos

Opción A - Crear seeder o ejecutar en tinker:

```php
use App\Models\TemplateTienda;

TemplateTienda::create([
    'codigo' => 'minimal',
    'nombre' => 'Template Minimal',
    'descripcion' => 'Diseño minimalista y limpio',
    'vista_index' => 'tienda.minimal_index',
    'vista_categoria' => 'tienda.minimal_categoria',
    'vista_producto' => 'tienda.minimal_producto',
    'layout' => 'tienda.minimal_layout',
    'preview_image' => 'images/templates/minimal-preview.jpg',
    'activo' => true,
    'orden' => 3,
    'configuracion' => [
        'color_primario' => '#000000',
        'fuente_principal' => 'Helvetica',
    ],
]);
```

Opción B - Agregar al `TemplateTiendaSeeder`:

```php
// En database/seeders/TemplateTiendaSeeder.php, agregar al array $templates:
[
    'codigo' => 'minimal',
    'nombre' => 'Template Minimal',
    // ... resto de campos
],
```

#### Paso 5: Crear Imagen Preview

Agregar imagen en: `public/images/templates/minimal-preview.jpg` (dimensión sugerida: 800x600px)

#### Paso 6: Limpiar Cache

```bash
php artisan cache:clear
```

---

### Templates Disponibles

| Código | Nombre | Descripción | Características | Vistas |
|--------|--------|-------------|-----------------|--------|
| `default` | Template Clásico | Template original elegante y moderno | Bootstrap 5, diseño limpio | `tienda.index`, `tienda.categoria`, `tienda.producto` |
| `brasilia` | Template Brasilia | Inspirado en tiendas de moda | Swiper, animaciones, adbars dinámicas | `tienda.brasilia_index`, `tienda.brasilia_categoria`, `tienda.brasilia_producto` |

---

### Convenciones de Naming

- **Código**: snake_case, único (ej: `brasilia`, `minimal_pro`)
- **Nombre**: Formato amigable (ej: "Template Brasilia")
- **Vistas**: `{codigo}_{tipo}.blade.php` (ej: `brasilia_index.blade.php`)
- **Strategy Class**: PascalCase + `TemplateStrategy` (ej: `BrasiliaTemplateStrategy`)
- **Assets**: `{codigo}-theme.css/js` (ej: `brasilia-theme.css`)
- **Partials**: `partials/{codigo}/{componente}.blade.php` (ej: `partials/brasilia/header.blade.php`)

---

### Estructura de Archivos

```
app/
├── Models/
│   └── TemplateTienda.php
├── Services/
│   └── Templates/
│       ├── Contracts/
│       │   └── TemplateStrategyInterface.php
│       ├── Strategies/
│       │   ├── DefaultTemplateStrategy.php
│       │   └── BrasiliaTemplateStrategy.php
│       ├── AbstractTemplateStrategy.php
│       └── TemplateResolver.php
├── Repositories/
│   └── TemplateRepository.php
└── Providers/
    └── TemplateServiceProvider.php

database/
├── migrations/
│   ├── 2025_10_17_115315_create_templates_tienda_table.php
│   └── 2025_10_17_115600_add_template_tienda_id_to_empresas_table.php
└── seeders/
    └── TemplateTiendaSeeder.php

resources/views/tienda/
├── layout.blade.php (Default)
├── index.blade.php
├── categoria.blade.php
├── producto.blade.php
├── brasilia_layout.blade.php (Brasilia)
├── brasilia_index.blade.php
├── brasilia_categoria.blade.php
├── brasilia_producto.blade.php
└── partials/
    └── brasilia/
        ├── header.blade.php
        ├── footer.blade.php
        ├── svg-sprite.blade.php
        └── modals.blade.php

public/
├── css/
│   └── brasilia-theme.css
├── js/
│   └── brasilia-theme.js
└── images/
    └── templates/
        ├── default-preview.jpg
        └── brasilia-preview.jpg
```

---

### API del Sistema

#### TemplateResolver

```php
// Obtener strategy para una empresa
$strategy = app(TemplateResolver::class)->resolveForEmpresa($empresa);

// Crear strategy manualmente
$template = TemplateTienda::find(1);
$strategy = $resolver->createStrategy($template);

// Limpiar cache de una empresa
$resolver->clearCache($empresa);

// Registrar nueva strategy dinámicamente
$resolver->registerStrategy('custom', CustomTemplateStrategy::class);

// Obtener mapa completo de strategies
$map = $resolver->getStrategyMap();
```

#### TemplateRepository

```php
$repo = app(TemplateRepository::class);

// Obtener template de empresa (con fallback a default)
$template = $repo->findByEmpresa($empresa);

// Obtener templates activos ordenados
$templates = $repo->getAllActivos();

// Obtener template por defecto
$default = $repo->getDefault();

// Obtener por código
$brasilia = $repo->findByCodigo('brasilia');

// Para selector en formulario (solo campos necesarios)
$templates = $repo->getForSelector();
```

#### TemplateStrategyInterface

```php
$strategy = app(TemplateResolver::class)->resolveForEmpresa($empresa);

// Obtener nombres de vistas
$viewIndex = $strategy->getViewIndex();      // 'tienda.brasilia_index'
$viewCategoria = $strategy->getViewCategoria();  // 'tienda.brasilia_categoria'
$viewProducto = $strategy->getViewProducto();   // 'tienda.brasilia_producto'
$layout = $strategy->getLayout();         // 'tienda.brasilia_layout'

// Preparar datos específicos del template
$preparedData = $strategy->prepareData($originalData);

// Obtener assets CSS/JS
$assets = $strategy->getAssets();
// ['css' => [...], 'js' => [...]]

// Obtener configuración
$config = $strategy->getConfig();
```

#### Modelo TemplateTienda

```php
// Scopes
$activos = TemplateTienda::activos()->get();
$ordenados = TemplateTienda::ordenados()->get();

// Obtener template por defecto
$default = TemplateTienda::getDefault();

// Obtener vistas del template
$template = TemplateTienda::find(1);
$vistas = $template->getVistas();
// ['index' => '...', 'categoria' => '...', 'producto' => '...', 'layout' => '...']

// Verificar si es default
$esDefault = $template->esDefault();

// URL de preview
$previewUrl = $template->preview_url; // Accessor
```

#### Modelo Empresa

```php
// Relación con template
$template = $empresa->templateTienda;

// Obtener template (con fallback)
$template = $empresa->getTemplate();
```

---

### Uso en Controllers

#### TiendaController (ya implementado)

```php
use App\Services\Templates\TemplateResolver;

class TiendaController extends Controller
{
    protected TemplateResolver $templateResolver;

    public function __construct(TemplateResolver $templateResolver)
    {
        $this->templateResolver = $templateResolver;
    }

    public function show($slug, Request $request)
    {
        $empresa = Empresa::where('slug', $slug)->firstOrFail();

        // Resolver estrategia
        $strategy = $this->templateResolver->resolveForEmpresa($empresa);

        // Preparar datos
        $data = $strategy->prepareData(compact('empresa', 'productos', 'categorias'));

        // Renderizar vista del template
        return view($strategy->getViewIndex(), $data);
    }
}
```

#### EmpresasController (ya implementado)

```php
use App\Repositories\TemplateRepository;
use App\Services\Templates\TemplateResolver;

class EmpresasController extends Controller
{
    public function form(TemplateRepository $templateRepo)
    {
        $templates = $templateRepo->getForSelector();
        return view('empresa.form', compact('empresa', 'templates'));
    }

    public function guardar(Request $request, TemplateResolver $templateResolver)
    {
        // Validar
        $request->validate([
            'template_tienda_id' => ['nullable', 'exists:templates_tienda,id'],
        ]);

        // Guardar empresa
        $empresa->save();

        // Limpiar cache si cambió template
        if ($empresa->wasChanged('template_tienda_id')) {
            $templateResolver->clearCache($empresa);
        }
    }
}
```

---

### Selección de Template en el Frontend

La empresa puede seleccionar su template desde `/empresa` → Formulario de configuración → Sección "Template de Tienda".

**Características del selector:**
- Grid responsive con cards visuales
- Imagen preview de cada template
- Descripción y características
- Badge "Por defecto" en el template principal
- Icono de check animado en template seleccionado
- Interacción click en todo el card
- Auto-selección del template por defecto en empresas nuevas

---

### Cache Management

El sistema usa cache de Laravel para optimizar performance:

**Key Pattern**: `template_strategy_empresa_{empresa_id}`
**TTL**: 3600 segundos (1 hora)

**Limpieza automática**:
- Al cambiar template desde configuración de empresa
- Método: `TemplateResolver::clearCache($empresa)`

**Limpieza manual**:
```bash
php artisan cache:clear
```

O en código:
```php
Cache::forget("template_strategy_empresa_{$empresaId}");
```

---

### Troubleshooting

#### Problema: Template no cambia después de seleccionar otro

**Solución**: Limpiar cache
```bash
php artisan cache:clear
php artisan view:clear
```

#### Problema: Error "Template strategy class not found"

**Solución**:
1. Verificar que la clase Strategy existe en `app/Services/Templates/Strategies/`
2. Verificar que está registrada en `TemplateResolver::$strategyMap`
3. Ejecutar `composer dump-autoload`

#### Problema: Las vistas no se encuentran

**Solución**:
1. Verificar que los archivos blade existen en `resources/views/tienda/`
2. Verificar que los nombres de vistas en la BD coinciden exactamente
3. Ejecutar `php artisan view:clear`

#### Problema: Error al guardar template en empresa

**Solución**:
1. Verificar que las migraciones se ejecutaron: `php artisan migrate:status`
2. Verificar que el seeder creó los templates: `SELECT * FROM templates_tienda;`
3. Verificar que el `template_tienda_id` es nullable en la tabla empresas

#### Problema: Selector de templates no aparece en formulario

**Solución**:
1. Verificar que `$templates` está en el compact del controller
2. Verificar que hay templates activos en BD
3. Revisar consola del navegador por errores JavaScript

---

### Testing

#### Test de Strategy

```php
use Tests\TestCase;
use App\Services\Templates\Strategies\BrasiliaTemplateStrategy;

class BrasiliaTemplateTest extends TestCase
{
    public function test_returns_correct_views()
    {
        $strategy = new BrasiliaTemplateStrategy();

        $this->assertEquals('tienda.brasilia_index', $strategy->getViewIndex());
        $this->assertEquals('tienda.brasilia_categoria', $strategy->getViewCategoria());
        $this->assertEquals('tienda.brasilia_producto', $strategy->getViewProducto());
        $this->assertEquals('tienda.brasilia_layout', $strategy->getLayout());
    }

    public function test_prepares_data_correctly()
    {
        $strategy = new BrasiliaTemplateStrategy(['mostrar_adbars' => true]);
        $data = $strategy->prepareData(['empresa' => $empresa]);

        $this->assertArrayHasKey('brasiliaConfig', $data);
        $this->assertTrue($data['brasiliaConfig']['showAdBars']);
    }
}
```

#### Test de Resolver

```php
use App\Models\Empresa;
use App\Models\TemplateTienda;
use App\Services\Templates\TemplateResolver;

class TemplateResolverTest extends TestCase
{
    public function test_resolves_correct_strategy_for_empresa()
    {
        $template = TemplateTienda::where('codigo', 'brasilia')->first();
        $empresa = Empresa::factory()->create(['template_tienda_id' => $template->id]);

        $resolver = app(TemplateResolver::class);
        $strategy = $resolver->resolveForEmpresa($empresa);

        $this->assertInstanceOf(BrasiliaTemplateStrategy::class, $strategy);
    }

    public function test_cache_works()
    {
        $empresa = Empresa::factory()->create();
        $resolver = app(TemplateResolver::class);

        $strategy1 = $resolver->resolveForEmpresa($empresa);
        $strategy2 = $resolver->resolveForEmpresa($empresa); // Debe venir de cache

        $this->assertSame($strategy1, $strategy2);
    }
}
```

---

### Migración de Empresas Existentes

Para asignar template default a empresas existentes que no tienen template:

```php
use App\Models\Empresa;
use App\Models\TemplateTienda;

$defaultTemplate = TemplateTienda::where('es_default', true)->first();

Empresa::whereNull('template_tienda_id')->update([
    'template_tienda_id' => $defaultTemplate->id,
]);
```

O ejecutar en tinker:
```bash
php artisan tinker
>>> $default = \App\Models\TemplateTienda::getDefault();
>>> \App\Models\Empresa::whereNull('template_tienda_id')->update(['template_tienda_id' => $default->id]);
```

---

### Extensibilidad Futura

El sistema está diseñado para ser extendido fácilmente:

1. **Nuevos métodos en Strategy**: Agregar a la interface y abstract class
2. **Configuraciones por template**: Usar campo JSON `configuracion`
3. **Assets dinámicos**: Cargar CSS/JS desde BD o archivos de configuración
4. **Preview en tiempo real**: Implementar iframe con preview antes de guardar
5. **Variantes de templates**: Crear strategies que extiendan otras (ej: `BrasiliaProTemplateStrategy extends BrasiliaTemplateStrategy`)
6. **Template marketplace**: Sistema de importación/exportación de templates
7. **Template builder**: Editor visual de templates (futuro)

---

### Comandos Útiles

```bash
# Ver templates en BD
php artisan tinker
>>> \App\Models\TemplateTienda::all()

# Crear nuevo template desde tinker
>>> \App\Models\TemplateTienda::create([...])

# Re-ejecutar seeder de templates
php artisan db:seed --class=TemplateTiendaSeeder

# Limpiar todo el cache
php artisan optimize:clear

# Ver qué empresa usa qué template
>>> \App\Models\Empresa::with('templateTienda')->get(['id', 'nombre', 'template_tienda_id'])

# Ver estadísticas de uso de templates
>>> \App\Models\TemplateTienda::withCount('empresas')->get()
```

---

### Notas Importantes

1. **Performance**: El cache de 1 hora es crucial para no resolver la strategy en cada request
2. **Backward Compatibility**: Empresas sin template_tienda_id usan automáticamente el default
3. **Nullability**: El campo `template_tienda_id` es nullable para permitir fallback
4. **Validación**: Siempre validar que el `template_tienda_id` existe antes de guardar
5. **Assets**: Los assets de cada template se cargan en el layout, no en las vistas individuales
6. **Blade Inheritance**: Todos los templates deben usar `@extends` para mantener DRY
7. **Partials**: Componentes reutilizables deben estar en `partials/{codigo}/`
8. **Configuration**: Usar el campo JSON `configuracion` para opciones específicas del template