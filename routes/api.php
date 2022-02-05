<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\UserController;

use App\Http\Controllers\CanchaController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ComprobanteController;

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

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'authenticate']);
Route::post('refresh-token', [UserController::class, 'refreshToken']);
Route::post('logout', [UserController::class, 'logout']);

Route::group(['middleware' => ['jwt.verify']], function() {
    /*AÑADE AQUI LAS RUTAS QUE QUIERAS PROTEGER CON JWT*/
});

Route::resource('user', UserController::class);
Route::post('usuariosConFiltro', [UserController::class, 'usuariosConFiltro']);

Route::resource('cancha', CanchaController::class);

Route::resource('horario', HorarioController::class);

Route::resource('reserva', ReservaController::class);
Route::post('reservasConFiltro', [ReservaController::class, 'reservasConFiltro']);
Route::post('reservaPorDia', [ReservaController::class, 'reservaPorDia']);
Route::post('reservaDisponible', [ReservaController::class, 'reservaDisponible']);

Route::resource('comprobante', ComprobanteController::class);
Route::post('comprobanteConFiltro', [ComprobanteController::class, 'comprobanteConFiltro']);

