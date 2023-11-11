<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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

Route::get('app/config', Controllers\SystemConfigController::class);
Route::post('auth', Controllers\Auth\LoginController::class);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::delete('auth', Controllers\Auth\LogoutController::class);
    Route::get('me', Controllers\Auth\UserController::class);


    Route::get('users', [Controllers\UserController::class, 'index']);
    Route::post('users', [Controllers\UserController::class, 'store']);
    Route::delete('users/{user:uuid}', [Controllers\UserController::class, 'destroy']);

    Route::get('config/{user:uuid}', [Controllers\ConfigController::class, 'index']);
    Route::post('config/{user:uuid}', [Controllers\ConfigController::class, 'store']);
    Route::delete('config/{user:uuid}', [Controllers\ConfigController::class, 'destroy']);

    Route::prefix('lists')->group(function () {
        Route::get('roles', Controllers\Lists\RoleController::class);
    });
});
