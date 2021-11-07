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

        // Actions
        Route::post('{id}/rate', 'App\Http\Controllers\Api\V1\Front\AvisController@rate');
        Route::post('{id}/comment', 'App\Http\Controllers\Api\V1\Front\AvisController@comment');
    });

    Route::prefix('admin')->group(function() {

        Route::get('dashboard/information', 'App\Http\Controllers\Api\V1\Admin\DashboardController@information');

        Route::prefix('users')->group(function() {
            Route::get('', 'App\Http\Controllers\Api\V1\Admin\UsersController@index');
            Route::get('{id}', 'App\Http\Controllers\Api\V1\Admin\UsersController@show');
            Route::delete('{id}', 'App\Http\Controllers\Api\V1\Admin\UsersController@delete');
        });
        Route::prefix('messages')->group(function() {
            Route::get('', 'App\Http\Controllers\Api\V1\Admin\MessagesController@index');
            Route::post('bulk-delete', 'App\Http\Controllers\Api\V1\Admin\MessagesController@bulkDelete');
            Route::delete('{id}', 'App\Http\Controllers\Api\V1\Admin\MessagesController@delete');
        });
        Route::prefix('avis')->group(function() {
            Route::get('', 'App\Http\Controllers\Api\V1\Admin\AvisController@index');
        });
        Route::prefix('comments')->group(function() {
            Route::get('', 'App\Http\Controllers\Api\V1\Admin\AvisCommentsController@index');
        });
        Route::prefix('ratings')->group(function() {
            Route::get('', 'App\Http\Controllers\Api\V1\Admin\AvisRatingsController@index');
        });
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
    });
});

