<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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

Route::get('api/auth/oauth/{provider}', [Controllers\Auth\OAuthController::class, 'redirectToProvider']);
Route::get('api/auth/oauth/{provider}/callback', [Controllers\Auth\OAuthController::class, 'handleProviderCallback']);

Route::get('{all}', function () {
    return File::get(public_path('index.html'));
})->where(['all' =>'.*']);
