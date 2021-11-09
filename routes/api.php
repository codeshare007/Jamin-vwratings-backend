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
Route::prefix('v1')->group(function () {

    // Public Methods
    Route::post('send-message', 'App\Http\Controllers\Api\V1\Front\SiteController@message');

    Route::prefix('avis')->group(function () {
        Route::get('', 'App\Http\Controllers\Api\V1\Front\AvisController@index');
        Route::post('create', 'App\Http\Controllers\Api\V1\Front\AvisController@create');
        Route::get('{id}', 'App\Http\Controllers\Api\V1\Front\AvisController@show');
        Route::post('{id}/rate', 'App\Http\Controllers\Api\V1\Front\AvisController@rate');
        Route::post('{id}/comment', 'App\Http\Controllers\Api\V1\Front\AvisController@comment');
    });

    // Auth methods
    Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
        Route::post('login', 'App\Http\Controllers\Api\V1\Front\AuthController@login');
        Route::post('register', 'App\Http\Controllers\Api\V1\Front\AuthController@register');
    });

    // Authenticated methods
    Route::middleware('auth:api')->group(function () {

        Route::get('comments', 'App\Http\Controllers\Api\V1\Front\SiteController@comments');

        Route::prefix('auth')->group(function () {
            Route::post('logout', 'App\Http\Controllers\Api\V1\Front\AuthController@logout');
            Route::post('refresh', 'App\Http\Controllers\Api\V1\Front\AuthController@refresh');
            Route::get('me', 'App\Http\Controllers\Api\V1\Front\AuthController@me');
        });

        // Admin methods
        Route::prefix('admin')->group(function () {
            Route::get('dashboard', 'App\Http\Controllers\Api\V1\Admin\AdminController@dashboard');

            Route::resource('users', 'App\Http\Controllers\Api\V1\Admin\UsersController');
            Route::resource('messages', 'App\Http\Controllers\Api\V1\Admin\MessagesController');
            Route::resource('avis', 'App\Http\Controllers\Api\V1\Admin\AvisController');
            Route::resource('comments', 'App\Http\Controllers\Api\V1\Admin\AvisCommentsController');
            Route::resource('ratings', 'App\Http\Controllers\Api\V1\Admin\AvisRatingsController');
        });
    });
});
