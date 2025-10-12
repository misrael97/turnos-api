<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


use App\Http\Controllers\AuthController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\ColaController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\AlertaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\NegociosController;
use App\Http\Controllers\SucursalesController;
use App\Http\Controllers\GestoresController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::apiResource('alertas', AlertaController::class);
    // Puedes agregar aquí otras rutas protegidas si lo deseas
});

// Rutas públicas
Route::apiResource('turnos', TurnoController::class);
Route::apiResource('colas', ColaController::class);
Route::apiResource('citas', CitaController::class);
Route::apiResource('negocios', NegociosController::class);
Route::apiResource('sucursales', SucursalesController::class);
Route::apiResource('gestores', GestoresController::class);
Route::get('reportes', [ReporteController::class, 'index']);
Route::get('reportes/avanzados', [ReporteController::class, 'avanzados']);
Route::get('turnos/espera', [TurnoController::class, 'enEspera']);
Route::post('turnos/llamar-siguiente', [TurnoController::class, 'llamarSiguiente']);
Route::get('turnos/tablero', [TurnoController::class, 'tableroActual']);
Route::get('turnos/historial', [TurnoController::class, 'historial']);
Route::post('turnos/{id}/cancelar', [TurnoController::class, 'cancelar']);
Route::post('turnos/{id}/reasignar', [TurnoController::class, 'reasignar']);
Route::post('turnos/validar-qr', [TurnoController::class, 'validarQR']);
Route::post('alertas/crear-por-espera', [AlertaController::class, 'crearPorEspera']);
Route::post('alertas/{id}/atender', [AlertaController::class, 'atender']);
