<?php

use App\Http\Controllers\ArchivoController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SolucionTicketController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TipoSoporteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// LOGIN
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);

// CONFIGURACIÓN
Route::get('/configuracion/getConfiguracion', [ConfiguracionController::class, 'getConfiguracion']);
Route::post('/configuracion/update', [ConfiguracionController::class, 'update']);

Route::prefix('admin')->group(function () {
    // USUARIOS
    Route::get('usuarios/getUsuario/{usuario}', [UserController::class, 'getUsuario']);
    Route::get('usuarios/getDocentes', [UserController::class, 'getDocentes']);
    Route::get('usuarios/getEstudiantes', [UserController::class, 'getEstudiantes']);
    Route::get('usuarios/userActual', [UserController::class, 'userActual']);
    Route::get('usuarios/getInfoBox', [UserController::class, 'getInfoBox']);
    Route::get('usuarios/getPermisos/{usuario}', [UserController::class, 'getPermisos']);
    Route::get('usuarios/getEncargadosRepresentantes', [UserController::class, 'getEncargadosRepresentantes']);
    Route::post('usuarios/actualizaContrasenia/{usuario}', [UserController::class, 'actualizaContrasenia']);
    Route::post('usuarios/actualizaFoto/{usuario}', [UserController::class, 'actualizaFoto']);
    Route::resource('usuarios', UserController::class)->only([
        'index', 'store', 'update', 'destroy', 'show'
    ]);

    // PERSONAL
    Route::resource('personals', PersonalController::class)->only([
        'index', 'store', 'show'
    ]);

    // TIPO DE SOPORTES
    Route::resource('tipo_soportes', TipoSoporteController::class)->only([
        'index', 'store', 'update', 'destroy', 'show'
    ]);

    // TICKETS
    Route::get('tickets/mis_tickets', [TicketController::class, 'mis_tickets'])->name('tickets.mis_tickets');
    Route::get('tickets/eliminados', [TicketController::class, 'tickets_eliminados'])->name('tickets.tickets_eliminados');
    Route::post('tickets/restaura_ticket/{ticket}', [TicketController::class, 'restaura_ticket'])->name('tickets.restaura_ticket');
    Route::post('tickets/setEstadoEnvio/{ticket}', [TicketController::class, 'setEstadoEnvio'])->name('tickets.setEstadoEnvio');
    Route::get('tickets/cantidadTicketsSinVer', [TicketController::class, 'cantidadTicketsSinVer'])->name('tickets.cantidadTicketsSinVer');
    Route::get('tickets/listaIncidencias/{ticket}', [TicketController::class, 'listaIncidencias'])->name('tickets.listaIncidencias');
    Route::resource('tickets', TicketController::class)->only([
        'index', 'store', 'update', 'destroy', 'show'
    ]);

    // SOLUCIONES / INCIDENCIAS
    Route::resource('solucion_tickets', SolucionTicketController::class)->only([
        'index', 'store', 'update', 'destroy', 'show'
    ]);

    // ARCHIVOS
    Route::get('archivos/descargar/{archivo}', [ArchivoController::class, 'descargar'])->name('archivos.descargar');
    Route::resource('archivos', ArchivoController::class)->only([
        'store', 'destroy'
    ]);

    // FAQ'S
    Route::get('faqs/lista', [FaqController::class, 'lista'])->name('faqs.lista');
    Route::resource('faqs', FaqController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);

    // REPORTES
    Route::post('reportes/usuarios', [ReporteController::class, 'usuarios']);
    Route::post('reportes/registros', [ReporteController::class, 'registros']);
    Route::post('reportes/soluciones', [ReporteController::class, 'soluciones']);
    Route::post('reportes/historial_tickets', [ReporteController::class, 'historial_tickets']);
    Route::post('reportes/atencion_tickets', [ReporteController::class, 'atencion_tickets']);
    Route::post('reportes/estado_tickets', [ReporteController::class, 'estado_tickets']);
});

// ---------------------------------------
Route::get('/{optional?}', function () {
    return view('app');
})->name('base_path')->where('optional', '.*');
