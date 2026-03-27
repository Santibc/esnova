<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\LlamadasController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CiudadController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\ActualizacionPreciosController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Ruta raíz - Muestra la tienda de la primera empresa (single-tenant)
Route::get('/', function () {
    $empresa = \App\Models\Empresa::orderBy('id')->first();

    if (!$empresa) {
        // Si no hay empresa, mostrar mensaje o redirigir al login
        return redirect()->route('login')->with('info', 'Por favor inicia sesión para configurar tu tienda.');
    }

    // Redirigir al TiendaController con el slug de la empresa
    return app(\App\Http\Controllers\TiendaController::class)->show($empresa->slug, request());
})->name('tienda.empresa'); // Ruta nombrada para compatibilidad

// Landing page deshabilitada - Single-tenant
// Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index']);
// Route::post('/formulario-contacto', [App\Http\Controllers\WelcomeController::class, 'enviarFormularioContacto'])->name('formulario.contacto');

// Ruta para el tema Brasilia (demo de tienda)
Route::get('/brasilia', function () {
    return view('tienda.brasilia_index');
})->name('tienda.brasilia');

Route::get('/brasilia/productos', function () {
    return view('tienda.brasilia_categoria');
})->name('tienda.brasilia.productos');

Route::get('/brasilia/producto/{slug?}', function ($slug = 'vestido-largo-lino') {
    return view('tienda.brasilia_producto');
})->name('tienda.brasilia.producto');

// Ruta para el tema Sport (demo de tienda)
Route::get('/sport', function () {
    return view('tienda.sport_index');
})->name('tienda.sport');
Route::get('/ajax/ciudades', [App\Http\Controllers\ClientesController::class, 'ciudadesAjax'])->name('ajax.ciudades');
Route::get('/dashboard',[HomeController::class, 'index'] )->middleware(['auth', 'verified', 'verificar.membresia'])->name('dashboard');
Route::get('ajax/ciudades', [CiudadController::class,'byDepartamento'])
     ->name('ajax.ciudades');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // MÓDULO DE USUARIOS DESHABILITADO - Single-tenant
    // Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuarios');
    // Route::get('/importar_usuarios', [UsuariosController::class, 'importar_usuarios'])->name('importar_usuarios');
    // Route::get('/usuarios_form/{user?}', [UsuariosController::class, 'form'])->name('usuarios.form');
    // Route::post('/usuarios/guardar', [UsuariosController::class, 'guardar'])->name('usuarios.guardar');
    // Route::post('/usuarios/cambiar-estado-empresa', [UsuariosController::class, 'cambiarEstadoEmpresa'])->name('usuarios.cambiar-estado-empresa');



//Clientes
    // Listado & AJAX





// Rutas de Productos - versión simplificada
Route::prefix('productos')->middleware(['auth', 'verificar.empresa', 'verificar.membresia'])->group(function () {
    Route::get('/', [ProductosController::class, 'index'])->name('productos');
    Route::get('/form/{producto?}', [ProductosController::class, 'form'])->name('productos.form');
    Route::post('/guardar', [ProductosController::class, 'guardar'])->name('productos.guardar');
    Route::post('/eliminar', [ProductosController::class, 'eliminar'])->name('productos.eliminar');
    Route::get('/{producto}/variantes-ajax', [ProductosController::class, 'variantesAjax'])->name('productos.variantes-ajax');
    Route::get('/{producto}/imagenes-ajax', [ProductosController::class, 'imagenesAjax'])->name('productos.imagenes-ajax');
    Route::get('/{producto}/precios-ajax', [ProductosController::class, 'preciosAjax'])->name('productos.precios-ajax');
});

// Rutas de Descuentos
Route::prefix('descuentos')->middleware(['auth', 'verificar.empresa'])->group(function () {
    Route::get('/', [App\Http\Controllers\DescuentosController::class, 'index'])->name('descuentos.index');
    Route::get('/create', [App\Http\Controllers\DescuentosController::class, 'create'])->name('descuentos.create');
    Route::post('/store', [App\Http\Controllers\DescuentosController::class, 'store'])->name('descuentos.store');
    Route::get('/{id}/edit', [App\Http\Controllers\DescuentosController::class, 'edit'])->name('descuentos.edit');
    Route::put('/{id}', [App\Http\Controllers\DescuentosController::class, 'update'])->name('descuentos.update');
    Route::delete('/{id}', [App\Http\Controllers\DescuentosController::class, 'destroy'])->name('descuentos.destroy');
    Route::post('/{id}/toggle', [App\Http\Controllers\DescuentosController::class, 'toggleEstado'])->name('descuentos.toggle');
    Route::get('/{id}/estadisticas', [App\Http\Controllers\DescuentosController::class, 'estadisticas'])->name('descuentos.estadisticas');
});
Route::get('actualizaciones/{id}/descargar', 
    [ActualizacionPreciosController::class, 'descargarArchivoActualizacion']
)->name('actualizaciones.descargar');


});
// Rutas del Catálogo Interactivo
// Flujo A: Acceso público por token
// Agregar estas rutas en routes/web.php

// Módulo de Enlaces de Acceso (autenticado)
Route::middleware(['auth', 'verificar.empresa', 'verificar.membresia'])->group(function () {
    // Enlaces temporales
    Route::get('/enlaces', [App\Http\Controllers\EnlacesController::class, 'index'])->name('enlaces');
    Route::get('/enlaces/crear', [App\Http\Controllers\EnlacesController::class, 'crear'])->name('enlaces.crear');
    Route::post('/enlaces/guardar', [App\Http\Controllers\EnlacesController::class, 'guardar'])->name('enlaces.guardar');
    Route::get('/enlaces/{enlace}/detalle', [App\Http\Controllers\EnlacesController::class, 'detalle'])->name('enlaces.detalle');
    Route::post('/enlaces/{enlace}/cambiar-estado', [App\Http\Controllers\EnlacesController::class, 'cambiarEstado'])->name('enlaces.cambiar-estado');
});

// Catálogo público con token (sin autenticación)
Route::get('/catalogo/{token}', [App\Http\Controllers\CatalogoController::class, 'mostrarPorToken'])->name('catalogo.token');

// Flujo B: Acceso autenticado (vendedor/admin)
Route::middleware(['auth', 'verificar.empresa', 'verificar.membresia'])->group(function () {
    Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo');
    Route::post('/catalogo/cliente', [CatalogoController::class, 'mostrarParaCliente'])->name('catalogo.cliente');
});

// Rutas AJAX del catálogo (pueden ser públicas o autenticadas)
Route::post('/catalogo/productos', [CatalogoController::class, 'obtenerProductos'])->name('catalogo.productos');
Route::get('/catalogo/producto/{producto}', [CatalogoController::class, 'detalleProducto'])->name('catalogo.producto.detalle');
Route::post('/catalogo/solicitud', [CatalogoController::class, 'guardarSolicitud'])->name('catalogo.solicitud.guardar');

// Rutas de Gestión de Solicitudes
Route::middleware(['auth', 'verificar.empresa', 'verificar.membresia'])->group(function () {
    Route::get('/solicitudes', [SolicitudController::class, 'index'])->name('solicitudes');
    Route::get('/solicitudes/{solicitud}/detalle', [SolicitudController::class, 'detalle'])->name('solicitudes.detalle');
    Route::post('/solicitudes/{solicitud}/aplicar', [SolicitudController::class, 'aplicar'])->name('solicitudes.aplicar');
});


// Rutas de Stock
Route::middleware(['auth', 'verificar.empresa', 'verificar.membresia'])->group(function () {
    
    // Rutas de Stock
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/', [App\Http\Controllers\StockController::class, 'index'])->name('index');
        Route::get('/dashboard', [App\Http\Controllers\StockController::class, 'dashboard'])->name('dashboard');
        Route::post('/entrada', [App\Http\Controllers\StockController::class, 'entrada'])->name('entrada');
        Route::post('/salida', [App\Http\Controllers\StockController::class, 'salida'])->name('salida');
        Route::post('/ajuste', [App\Http\Controllers\StockController::class, 'ajuste'])->name('ajuste');
        Route::post('/configurar', [App\Http\Controllers\StockController::class, 'configurar'])->name('configurar');
        Route::get('/historial', [App\Http\Controllers\StockController::class, 'historial'])->name('historial');
        Route::get('/{id}/obtener', [App\Http\Controllers\StockController::class, 'obtenerStock'])->name('obtener');
        Route::post('/inicializar-todos', [App\Http\Controllers\StockController::class, 'inicializarTodos'])->name('inicializar-todos');
        Route::get('/productos-json', [App\Http\Controllers\StockController::class, 'productosJson'])->name('productos-json');
        Route::get('/reporte-movimiento', [App\Http\Controllers\StockController::class, 'reporteMovimientos'])->name('reporte-movimiento');
        Route::post('/importar', [App\Http\Controllers\StockController::class, 'importar'])->name('importar');
        Route::get('/exportar', [App\Http\Controllers\StockController::class, 'exportar'])->name('exportar');
    });
});

// Agregar ruta AJAX para ver stock desde productos
Route::get('/productos/{producto}/stock-ajax', [App\Http\Controllers\ProductosController::class, 'stockAjax'])->name('productos.stock-ajax');

// Rutas para solicitudes
Route::get('/solicitudes/{solicitud}/pdf', [SolicitudController::class, 'descargarPdf'])->name('solicitudes.pdf');
Route::get('/solicitudes/exportar-excel', [SolicitudController::class, 'exportarExcel'])->name('solicitudes.exportar-excel');
Route::middleware(['auth', 'verificar.empresa', 'verificar.membresia'])->group(function () {
    // ... otras rutas existentes ...
    
    // Actualización de precios
    Route::post('/productos/actualizar-precios-excel', [ProductosController::class, 'actualizarPreciosExcel'])->name('productos.actualizar-precios-excel');
    Route::get('/productos/historial-precios', [ActualizacionPreciosController::class, 'historial'])->name('productos.historial-precios');
    Route::get('/productos/actualizacion-precios/{id}', [ActualizacionPreciosController::class, 'verDetalle'])->name('productos.actualizacion-precios.detalle');
    
    // Rutas para descargar plantillas
    Route::get('/productos/descargar-plantilla-csv', [ActualizacionPreciosController::class, 'descargarPlantillaCsv'])->name('productos.descargar-plantilla-csv');
    Route::get('/productos/descargar-plantilla-excel', [ActualizacionPreciosController::class, 'descargarPlantillaExcel'])->name('productos.descargar-plantilla-excel');
});
// Agregar estas rutas al archivo routes/web.php dentro del middleware 'auth'

// Rutas de Empresa
Route::prefix('empresa')->middleware(['auth', 'verificar.empresa', 'verificar.membresia'])->name('empresa.')->group(function () {
    Route::get('/', [App\Http\Controllers\EmpresasController::class, 'index'])->name('index');
    Route::get('/crear', [App\Http\Controllers\EmpresasController::class, 'form'])->name('crear');
    Route::get('/editar', [App\Http\Controllers\EmpresasController::class, 'form'])->name('form');
    Route::post('/guardar', [App\Http\Controllers\EmpresasController::class, 'guardar'])->name('guardar');
    Route::post('/cambiar-estado', [App\Http\Controllers\EmpresasController::class, 'cambiarEstado'])->name('cambiar-estado');
    Route::get('/preview', [App\Http\Controllers\EmpresasController::class, 'preview'])->name('preview');
    Route::get('/banner', [App\Http\Controllers\EmpresasController::class, 'editarBanner'])->name('banner');
    Route::post('/banner/guardar', [App\Http\Controllers\EmpresasController::class, 'guardarBanner'])->name('banner.guardar');
});

// Ruta pública para ver la tienda
/* Route::get('/tienda/{slug}', [App\Http\Controllers\TiendaController::class, 'show'])->name('tienda.empresa'); */
Route::get('/tienda/acceso/{token}', [App\Http\Controllers\TiendaController::class, 'acceso'])->name('tienda.acceso');
Route::middleware(['auth', 'verificar.empresa', 'verificar.membresia'])->group(function () {
    
    // Rutas de Categorías
    Route::prefix('categorias')->name('categorias.')->group(function () {
        Route::get('/', [App\Http\Controllers\CategoriasController::class, 'index'])->name('index');
        Route::get('/form/{categoria?}', [App\Http\Controllers\CategoriasController::class, 'form'])->name('form');
        Route::post('/guardar', [App\Http\Controllers\CategoriasController::class, 'guardar'])->name('guardar');
     Route::post('/{categoria}/cambiar-estado', [App\Http\Controllers\CategoriasController::class, 'cambiarEstado'])->name('cambiar-estado');
        Route::delete('/{categoria}', [App\Http\Controllers\CategoriasController::class, 'eliminar'])->name('eliminar');
    });
    
    // Alias para la ruta index
    Route::get('/categorias', [App\Http\Controllers\CategoriasController::class, 'index'])->name('categorias');
    
    // Rutas de Clientes
    Route::prefix('clientes')->name('clientes.')->group(function () {
        Route::get('/', [App\Http\Controllers\ClientesController::class, 'index'])->name('index');
        Route::get('/form/{cliente?}', [App\Http\Controllers\ClientesController::class, 'form'])->name('form');
        Route::post('/guardar', [App\Http\Controllers\ClientesController::class, 'guardar'])->name('guardar');
        Route::post('/{cliente}/cambiar-estado', [App\Http\Controllers\ClientesController::class, 'cambiarEstado'])->name('cambiar-estado');
        Route::get('/{cliente}/enlaces-ajax', [App\Http\Controllers\ClientesController::class, 'enlacesAjax'])->name('enlaces-ajax');
    });
    
    // Alias para la ruta index
    Route::get('/clientes', [App\Http\Controllers\ClientesController::class, 'index'])->name('clientes');
});
// Agregar estas rutas al final de web.php, antes del require __DIR__.'/auth.php';

// ========== RUTAS DE GESTIÓN DE COMPRAS (AUTENTICADAS) ==========
Route::middleware(['auth', 'verificar.empresa'])->prefix('compras')->name('compras.')->group(function () {
    Route::get('/', [App\Http\Controllers\ComprasController::class, 'index'])->name('index');
    Route::get('/{compra}', [App\Http\Controllers\ComprasController::class, 'show'])->name('show');
    Route::post('/{compra}/cambiar-estado', [App\Http\Controllers\ComprasController::class, 'cambiarEstado'])->name('cambiar-estado');
    Route::post('/{compra}/actualizar-envio', [App\Http\Controllers\ComprasController::class, 'actualizarEnvio'])->name('actualizar-envio');
    Route::get('/{compra}/timeline', [App\Http\Controllers\ComprasController::class, 'timeline'])->name('timeline');
    Route::get('/exportar/excel', [App\Http\Controllers\ComprasController::class, 'exportar'])->name('exportar');
    Route::post('/{compra}/aprobar-pago', [App\Http\Controllers\ComprasController::class, 'aprobarPagoOtro'])->name('aprobar-pago');
    Route::post('/{compra}/rechazar-pago', [App\Http\Controllers\ComprasController::class, 'rechazarPagoOtro'])->name('rechazar-pago');
});

// Alias para la ruta index de compras
Route::get('/compras', [App\Http\Controllers\ComprasController::class, 'index'])
    ->middleware(['auth', 'verificar.empresa'])
    ->name('compras');

// ========== RUTAS DE GESTIÓN DE CALIFICACIONES (ADMIN) ==========
Route::middleware(['auth', 'verificar.empresa'])->prefix('calificaciones')->name('calificaciones.')->group(function () {
    Route::get('/', [App\Http\Controllers\CalificacionesAdminController::class, 'index'])->name('index');
    Route::get('/aprobadas', [App\Http\Controllers\CalificacionesAdminController::class, 'aprobadas'])->name('aprobadas');
    Route::get('/respuestas', [App\Http\Controllers\CalificacionesAdminController::class, 'respuestas'])->name('respuestas');
    Route::post('/{id}/aprobar', [App\Http\Controllers\CalificacionesAdminController::class, 'aprobar'])->name('aprobar');
    Route::delete('/{id}/rechazar', [App\Http\Controllers\CalificacionesAdminController::class, 'rechazar'])->name('rechazar');
});

// Ruta pública para guardar reseñas de productos
Route::post('/producto/{slug}/resena', [App\Http\Controllers\TiendaController::class, 'guardarResena'])
    ->name('tienda.producto.resena');

// Rutas públicas para respuestas y reacciones
Route::prefix('resenas')->name('resenas.')->group(function () {
    Route::post('/{calificacion}/respuesta', [App\Http\Controllers\TiendaController::class, 'guardarRespuesta'])->name('respuesta');
    Route::post('/{calificacion}/reaccion', [App\Http\Controllers\TiendaController::class, 'toggleReaccion'])->name('reaccion');
});


// ========== RUTAS DE BLOG (ADMIN) ==========
Route::prefix('admin-blog')
    ->middleware(['auth', 'verificar.empresa'])
    ->name('blog.')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\BlogController::class, 'index'])->name('index');
        Route::get('/form/{blogPost?}', [App\Http\Controllers\BlogController::class, 'form'])->name('form');
        Route::post('/guardar', [App\Http\Controllers\BlogController::class, 'guardar'])->name('guardar');
        Route::post('/{blogPost}/cambiar-estado', [App\Http\Controllers\BlogController::class, 'cambiarEstado'])->name('cambiar-estado');
        Route::delete('/{blogPost}/eliminar', [App\Http\Controllers\BlogController::class, 'eliminar'])->name('eliminar');
        // Categorías del blog
        Route::get('/categorias', [App\Http\Controllers\BlogController::class, 'categorias'])->name('categorias');
        Route::post('/categorias/guardar', [App\Http\Controllers\BlogController::class, 'guardarCategoria'])->name('categorias.guardar');
        Route::delete('/categorias/{blogCategoria}/eliminar', [App\Http\Controllers\BlogController::class, 'eliminarCategoria'])->name('categorias.eliminar');
        // Configuración del blog
        Route::get('/configuracion', [App\Http\Controllers\BlogController::class, 'configuracion'])->name('configuracion');
        Route::post('/configuracion/guardar', [App\Http\Controllers\BlogController::class, 'guardarConfiguracion'])->name('configuracion.guardar');
    });

// Webhook de Wompi (sin CSRF)
Route::post('/webhooks/wompi', [App\Http\Controllers\WebhookController::class, 'wompi'])
    ->name('webhooks.wompi')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

require __DIR__.'/auth.php';

// ========== RUTAS DE TIENDA (DEBEN IR AL FINAL) ==========
Route::prefix('admin')->name('admin.')->group(function () {
    // MÓDULO DE COMISIONES DESHABILITADO - Single-tenant
    // Route::get('/dashboard', [App\Http\Controllers\DashboardAdminController::class, 'index'])->name('dashboard');
    // Route::get('/dashboard/empresa/{id}', [App\Http\Controllers\DashboardAdminController::class, 'detalleEmpresa'])->name('dashboard.empresa');
    // Route::post('/dashboard/pagar', [App\Http\Controllers\DashboardAdminController::class, 'marcarComoPagadas'])->name('dashboard.pagar');
    // Route::get('/dashboard/exportar', [App\Http\Controllers\DashboardAdminController::class, 'exportarReporte'])->name('dashboard.exportar');

    // MÓDULO DE DASHBOARD MEMBRESÍAS DESHABILITADO - Single-tenant
    // Route::get('/dashboard/membresias', [App\Http\Controllers\DashboardAdminController::class, 'dashboardMembresias'])->name('dashboard.membresias');
    // Route::get('/dashboard/membresias/exportar', [App\Http\Controllers\DashboardAdminController::class, 'exportarReporteMembresias'])->name('dashboard.membresias.exportar');

    // MÓDULO DE PLANES DE MEMBRESÍA DESHABILITADO - Single-tenant
    // Route::prefix('planes-membresia')->name('planes-membresia.')->group(function () {
    //     Route::get('/', [App\Http\Controllers\PlanMembresiaController::class, 'index'])->name('index');
    //     Route::get('/form/{plan?}', [App\Http\Controllers\PlanMembresiaController::class, 'form'])->name('form');
    //     Route::post('/guardar', [App\Http\Controllers\PlanMembresiaController::class, 'guardar'])->name('guardar');
    //     Route::post('/{plan}/cambiar-estado', [App\Http\Controllers\PlanMembresiaController::class, 'cambiarEstado'])->name('cambiar-estado');
    //     Route::delete('/{plan}', [App\Http\Controllers\PlanMembresiaController::class, 'eliminar'])->name('eliminar');
    // });

});

// MÓDULO DE MEMBRESÍAS DESHABILITADO - Single-tenant
// Route::middleware(['auth'])->group(function () {
//     // Membresías
//     Route::prefix('membresias')->name('membresias.')->group(function () {
//         Route::get('/', [App\Http\Controllers\MembresiaController::class, 'index'])->name('index');
//         Route::get('/historial', [App\Http\Controllers\MembresiaController::class, 'historial'])->name('historial');
//         Route::get('/{slug}', [App\Http\Controllers\MembresiaController::class, 'show'])->name('show');
//         Route::post('/{plan}/comprar', [App\Http\Controllers\MembresiaController::class, 'comprar'])->name('comprar');
//         Route::post('/cancelar', [App\Http\Controllers\MembresiaController::class, 'cancelar'])->name('cancelar');
//     });
// });

// Ruta pública para confirmación de pago de membresía - DESHABILITADA
// Route::get('/membresias/pago/confirmacion/{referencia}', [App\Http\Controllers\MembresiaController::class, 'confirmarPago'])->name('membresias.pago.confirmacion');

// ========== SITEMAP ==========
Route::get('/sitemap.xml', function () {
    $path = public_path('sitemap.xml');
    if (!file_exists($path)) {
        \App\Services\SitemapService::generar();
    }
    return response()->file($path, ['Content-Type' => 'application/xml']);
})->name('sitemap');

// ========== RUTAS DE TIENDA PÚBLICA (SINGLE-TENANT - SIN SLUG) ==========

// Blog público
Route::get('/blog', [App\Http\Controllers\TiendaController::class, 'blogIndex'])
    ->name('tienda.blog');
Route::get('/blog/{slug}', [App\Http\Controllers\TiendaController::class, 'blogPost'])
    ->name('tienda.blog.post');

// Catálogo de productos (con filtros por categoría, precio, etc.)
Route::get('/catalogo', [App\Http\Controllers\TiendaController::class, 'categorias'])
    ->name('tienda.categorias');

// Producto individual
Route::get('/producto/{slug}', [App\Http\Controllers\TiendaController::class, 'producto'])
    ->name('tienda.producto');

// Carrito
Route::get('/carrito', [App\Http\Controllers\TiendaController::class, 'verCarrito'])
    ->name('tienda.carrito');

Route::post('/carrito/agregar', [App\Http\Controllers\TiendaController::class, 'agregarCarrito'])
    ->name('tienda.carrito.agregar');

Route::post('/carrito/actualizar', [App\Http\Controllers\TiendaController::class, 'actualizarCarrito'])
    ->name('tienda.carrito.actualizar');

Route::post('/carrito/quitar', [App\Http\Controllers\TiendaController::class, 'quitarDelCarrito'])
    ->name('tienda.carrito.quitar');

// Descuentos
Route::post('/carrito/aplicar-descuento', [App\Http\Controllers\TiendaController::class, 'aplicarDescuento'])
    ->name('tienda.carrito.aplicar-descuento');

Route::post('/carrito/remover-descuento', [App\Http\Controllers\TiendaController::class, 'removerDescuento'])
    ->name('tienda.carrito.remover-descuento');

Route::post('/stock/info', [App\Http\Controllers\TiendaController::class, 'obtenerStockInfo'])
    ->name('tienda.stock.info');

Route::post('/carrito/validar-stock', [App\Http\Controllers\TiendaController::class, 'validarStockCarrito'])
    ->name('tienda.carrito.validar-stock');

// Checkout y pago
Route::get('/checkout', [App\Http\Controllers\TiendaController::class, 'checkout'])
    ->name('tienda.checkout');

Route::post('/procesar-compra', [App\Http\Controllers\TiendaController::class, 'procesarCompra'])
    ->name('tienda.procesar-compra');

// Confirmación de pago (callback de Wompi)
Route::get('/pago/confirmacion/{referencia}', [App\Http\Controllers\TiendaController::class, 'confirmarPago'])
    ->name('tienda.pago.confirmacion');

// Página de pago pendiente
Route::get('/pago/pendiente/{referencia}', function($referencia) {
    $empresa = \App\Models\Empresa::orderBy('id')->firstOrFail();
    $transaccion = \App\Models\TransaccionPago::where('referencia_transaccion', $referencia)->firstOrFail();

    return view('tienda.pago-pendiente', compact('empresa', 'transaccion'));
})->name('tienda.pago.pendiente');

// ============================================
// PANEL DE CLIENTE - Mis Compras
// ============================================
Route::middleware(['auth'])->prefix('cliente')->group(function () {
    Route::get('/mis-compras', [App\Http\Controllers\Cliente\MisComprasController::class, 'index'])
        ->name('cliente.compras');
    Route::get('/mis-compras/{id}', [App\Http\Controllers\Cliente\MisComprasController::class, 'show'])
        ->name('cliente.compras.show');
});
