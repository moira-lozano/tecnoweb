<?php

use App\Http\Controllers\CallBackAdminController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ComisionController;
use App\Http\Controllers\ConsultarAdminController;
use App\Http\Controllers\DetalleComisionVendendorController;
use App\Http\Controllers\GenerarCobroController;
use App\Http\Controllers\LicenciaController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\PageViewController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ProductosLandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SerieController;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Landing');
})->name('home');

Route::get('/landing', function () {
    return Inertia::render('Landing');
})->name('landing');

Route::get('/landing/productos/search', [WelcomeController::class, 'search'])->name('landing.productos.search');
Route::get('/landing/productos', [ProductosLandingController::class, 'index'])->name('landing.productos');
Route::get('/landing/misproductos', [ProductosLandingController::class, 'misproductos'])->name('landing.mis.productos');

Route::post('/pagos/callback', [CallBackAdminController::class, '__invoke'])->name('admin.pagos.callback');

// CONSULTAR PAGO
Route::post('/landing/pagos/create', [PagoController::class, 'create'])->name('landing.pagos.create');
Route::post('/landing/pagos/generarCobro', [GenerarCobroController::class, 'generarCobro'])->name('landing.pagos.generarCobro');
Route::get('/landing/pagos/consultar', [ConsultarAdminController::class, '__invoke'])->name('landing.pagos.consultar');
Route::get('/landing/pagos/formulario', [PagoController::class, 'create'])->name('landing.pagos.formulario');

// RUTA TEMPORAL PARA DEBUGGING
Route::post('/debug-pago', [GenerarCobroController::class, 'debug'])->name('landing.pagos.debug');
Route::post('/test-api-qr', [GenerarCobroController::class, 'testApiQR'])->name('test.api.qr');

// Dashboard solo para gerentes y vendedores
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'spatie.role:gerente,vendedor'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas admin solo para gerentes y vendedores
Route::middleware(['auth', 'spatie.role:gerente,vendedor'])->group(function () {
    
    // GESTIONAR ROLES
    Route::get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles');
    Route::get('/admin/roles/create', [RoleController::class, 'create'])->name('admin.roles.create');
    Route::post('/admin/roles/register', [RoleController::class, 'store'])->name('admin.roles.store');
    Route::get('/admin/roles/edit/{role}', [RoleController::class, 'edit'])->name('admin.roles.edit');
    Route::post('/admin/roles/edit/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
    Route::delete('/admin/roles/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');

    // GESTIONAR USUARIOS
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users/register', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/edit/{user}', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::post('/admin/users/edit/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // GESTIONAR CATEGORIAS
    Route::get('/admin/categorias', [CategoriaController::class, 'index'])->name('admin.categorias');
    Route::get('/admin/categorias/create', [CategoriaController::class, 'create'])->name('admin.categorias.create');
    Route::post('/admin/categorias/register', [CategoriaController::class, 'store'])->name('admin.categorias.store');
    Route::get('/admin/categorias/edit/{categoria}', [CategoriaController::class, 'edit'])->name('admin.categorias.edit');
    Route::post('/admin/categorias/edit/{categoria}', [CategoriaController::class, 'update'])->name('admin.categorias.update');
    Route::delete('/admin/categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('admin.categorias.destroy');

    // GESTIONAR LICENCIAS
    Route::get('/admin/licencias', [LicenciaController::class, 'index'])->name('admin.licencias');
    Route::get('/admin/licencias/create', [LicenciaController::class, 'create'])->name('admin.licencias.create');
    Route::post('/admin/licencias/register', [LicenciaController::class, 'store'])->name('admin.licencias.store');
    Route::get('/admin/licencias/edit/{producto}', [LicenciaController::class, 'edit'])->name('admin.licencias.edit');
    Route::post('/admin/licencias/edit/{producto}', [LicenciaController::class, 'update'])->name('admin.licencias.update');
    Route::delete('/admin/licencias/{producto}', [LicenciaController::class, 'destroy'])->name('admin.licencias.destroy');

    // GESTIONAR MARCAS
    Route::get('/admin/marcas', [MarcaController::class, 'index'])->name('admin.marcas');
    Route::get('/admin/marcas/create', [MarcaController::class, 'create'])->name('admin.marcas.create');
    Route::post('/admin/marcas/register', [MarcaController::class, 'store'])->name('admin.marcas.store');
    Route::get('/admin/marcas/edit/{marca}', [MarcaController::class, 'edit'])->name('admin.marcas.edit');
    Route::post('/admin/marcas/edit/{marca}', [MarcaController::class, 'update'])->name('admin.marcas.update');
    Route::delete('/admin/marcas/{marca}', [MarcaController::class, 'destroy'])->name('admin.marcas.destroy');

    // GESTIONAR SERIES
    Route::get('/admin/series', [SerieController::class, 'index'])->name('admin.series');
    Route::get('/admin/series/create', [SerieController::class, 'create'])->name('admin.series.create');
    Route::post('/admin/series/register', [SerieController::class, 'store'])->name('admin.series.store');
    Route::get('/admin/series/edit/{serie}', [SerieController::class, 'edit'])->name('admin.series.edit');
    Route::post('/admin/series/edit/{serie}', [SerieController::class, 'update'])->name('admin.series.update');
    Route::delete('/admin/series/{serie}', [SerieController::class, 'destroy'])->name('admin.series.destroy');

    // GESTIONAR COMISIONES
    Route::get('/admin/comisiones', [ComisionController::class, 'index'])->name('admin.comisiones');
    Route::get('/admin/comisiones/create', [ComisionController::class, 'create'])->name('admin.comisiones.create');
    Route::post('/admin/comisiones/register', [ComisionController::class, 'store'])->name('admin.comisiones.store');
    Route::get('/admin/comisiones/edit/{comision}', [ComisionController::class, 'edit'])->name('admin.comisiones.edit');
    Route::post('/admin/comisiones/edit/{comision}', [ComisionController::class, 'update'])->name('admin.comisiones.update');
    Route::delete('/admin/comisiones/{comision}', [ComisionController::class, 'destroy'])->name('admin.comisiones.destroy');

    // GESTIONAR DETALLE COMISIONES VENDEDOR
    Route::get('/admin/detallecomisionvendedor', [DetalleComisionVendendorController::class, 'index'])->name('admin.detallecomisionvendedor');

    // GESTIONAR PERSONAS
    Route::get('/admin/personas', [PersonaController::class, 'index'])->name('admin.personas');
    Route::get('/admin/personas/create', [PersonaController::class, 'create'])->name('admin.personas.create');
    Route::post('/admin/personas/register', [PersonaController::class, 'store'])->name('admin.personas.store');
    Route::get('/admin/personas/edit/{persona}', [PersonaController::class, 'edit'])->name('admin.personas.edit');
    Route::post('/admin/personas/edit/{persona}', [PersonaController::class, 'update'])->name('admin.personas.update');
    Route::delete('/admin/personas/{persona}', [PersonaController::class, 'destroy'])->name('admin.personas.destroy');

    // GESTIONAR TIPOS
    Route::get('/admin/tipos', [TipoController::class, 'index'])->name('admin.tipos');
    Route::get('/admin/tipos/create', [TipoController::class, 'create'])->name('admin.tipos.create');
    Route::post('/admin/tipos/register', [TipoController::class, 'store'])->name('admin.tipos.store');
    Route::get('/admin/tipos/edit/{tipo}', [TipoController::class, 'edit'])->name('admin.tipos.edit');
    Route::post('/admin/tipos/edit/{tipo}', [TipoController::class, 'update'])->name('admin.tipos.update');
    Route::delete('/admin/tipos/{tipo}', [TipoController::class, 'destroy'])->name('admin.tipos.destroy');

    // GESTIONAR PAGOS
    Route::get('/admin/pagos',[PagoController::class, 'index'])->name('admin.pagos');
    Route::get('/admin/pagos/create',[PagoController::class, 'create'])->name('admin.pagos.create');
    Route::post('/admin/pagos/store',[PagoController::class, 'store'])->name('admin.pagos.store');
    Route::get('/admin/pagos/edit/{pago}',[PagoController::class, 'edit'])->name('admin.pagos.edit');
    Route::patch('/admin/pagos/update/{pago}', [PagoController::class, 'update'])->name('admin.pagos.update');
    Route::delete('admin/pagos/destroy/{pago}',  [PagoController::class, 'destroy'])->name('admin.pagos.destroy');

    // GESTIONAR VENTAS
    Route::get('/admin/ventas',[VentaController::class, 'index'])->name('admin.ventas');
    Route::get('/admin/ventas/catalogo',[VentaController::class, 'catalogo'])->name('admin.ventas.catalogo');
    Route::get('/admin/ventas/create',[VentaController::class, 'create'])->name('admin.ventas.create');
    Route::post('/admin/ventas/store',[VentaController::class, 'store'])->name('admin.ventas.store');
    Route::get('/admin/ventas/edit/{venta}',[VentaController::class, 'edit'])->name('admin.ventas.edit');
    Route::patch('/admin/ventas/update/{venta}', [VentaController::class, 'update'])->name('admin.ventas.update');
    Route::delete('admin/ventas/destroy/{venta}',  [VentaController::class, 'destroy'])->name('admin.ventas.destroy');

    // GESTIONAR REPORTES Y ESTADISTICAS
    Route::get('/admin/reportes',[ReporteController::class, 'index'])->name('admin.reportes');
    Route::post('/send-report', [ReporteController::class, 'sendReport']);
    
    // GESTIONAR VIEWS
    Route::get('/admin/views',[PageViewController::class, 'index'])->name('admin.views');
});

require __DIR__.'/auth.php';