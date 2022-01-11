<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserController;

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

Route::post('jwt/register', [UserController::class, 'register']);
Route::post('jwt/login', [UserController::class, 'authenticate']);
Route::post('jwt/refresh-token', [UserController::class, 'authenticate']);
Route::post('jwt/logout', [UserController::class, 'authenticate']);

Route::group(['middleware' => ['jwt.verify']], function() {
    /*AÑADE AQUI LAS RUTAS QUE QUIERAS PROTEGER CON JWT*/
});