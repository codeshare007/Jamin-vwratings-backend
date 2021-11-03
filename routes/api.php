<?php
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

Route::get('v1/migrate', 'App\Http\Controllers\Api\V1\Front\TestController@databaseMigration');

Route::prefix('v1')->group(function () {

    Route::prefix('avis')->group(function() {
        Route::get('', 'App\Http\Controllers\Api\V1\Front\AvisController@index');
        Route::post('create', 'App\Http\Controllers\Api\V1\Front\AvisController@create');
        Route::get('{id}', 'App\Http\Controllers\Api\V1\Front\AvisController@show');
        Route::post('{id}/send', 'App\Http\Controllers\Api\V1\Front\AvisController@send');
    });

    Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
        Route::post('login', 'App\Http\Controllers\Api\V1\Front\AuthController@login');
        Route::post('register', 'App\Http\Controllers\Api\V1\Front\AuthController@register');
    });

    Route::middleware('auth:api')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('logout', 'App\Http\Controllers\Api\V1\Front\AuthController@logout');
            Route::post('refresh', 'App\Http\Controllers\Api\V1\Front\AuthController@refresh');
            Route::get('me', 'App\Http\Controllers\Api\V1\Front\AuthController@me');
        });

        Route::prefix('admin')->group(function() {

        });
    });
});

