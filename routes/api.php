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


    Route::post('reset-password', 'App\Http\Controllers\Api\V1\Front\AuthController@sendPasswordResetLink');
    Route::post('reset/password', 'App\Http\Controllers\Api\V1\Front\AuthController@callResetPassword');

    // Public Methods
    Route::post('tracker', 'App\Http\Controllers\Api\V1\Front\SiteController@sendTracker');
    Route::post('end-promo', 'App\Http\Controllers\Api\V1\Front\SiteController@endPromo');
    Route::post('send-message', 'App\Http\Controllers\Api\V1\Front\SiteController@message');

    // Parties
    Route::resource('parties', 'App\Http\Controllers\Api\V1\Front\PartiesController');
    Route::post('parties/{id}/rate', 'App\Http\Controllers\Api\V1\Front\PartiesController@rate');
    Route::post('parties/{id}/comment', 'App\Http\Controllers\Api\V1\Front\PartiesController@comment');

    // Avis
    Route::resource('avis', 'App\Http\Controllers\Api\V1\Front\AvisController');
    Route::post('avis/{id}/rate', 'App\Http\Controllers\Api\V1\Front\AvisController@rate');
    Route::post('avis/{id}/comment', 'App\Http\Controllers\Api\V1\Front\AvisController@comment');

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
            Route::get('dashboard/hits', 'App\Http\Controllers\Api\V1\Admin\AdminController@getHits');
            Route::post('dashboard/hits', 'App\Http\Controllers\Api\V1\Admin\AdminController@changeHits');

            Route::resource('users', 'App\Http\Controllers\Api\V1\Admin\UsersController');
            Route::resource('messages', 'App\Http\Controllers\Api\V1\Admin\MessagesController');
            Route::resource('avis', 'App\Http\Controllers\Api\V1\Admin\AvisController')->names('admin.avis');
            Route::resource('comments', 'App\Http\Controllers\Api\V1\Admin\AvisCommentsController');
            Route::resource('ratings', 'App\Http\Controllers\Api\V1\Admin\AvisRatingsController');
            Route::resource('campaigns', 'App\Http\Controllers\Api\V1\Admin\AdsCampaignsController');

            Route::post('users/bulk-delete', 'App\Http\Controllers\Api\V1\Admin\UsersController@bulkDelete');
            Route::post('messages/bulk-delete', 'App\Http\Controllers\Api\V1\Admin\MessagesController@bulkDelete');
            Route::post('avis/bulk-delete', 'App\Http\Controllers\Api\V1\Admin\AvisController@bulkDelete');
            Route::post('comments/bulk-delete', 'App\Http\Controllers\Api\V1\Admin\AvisCommentsController@bulkDelete');
            Route::post('ratings/bulk-delete', 'App\Http\Controllers\Api\V1\Admin\AvisRatingsController@bulkDelete');

            Route::post('comments/bulk-opinion', 'App\Http\Controllers\Api\V1\Admin\AvisCommentsController@bulkOpinion');
            Route::post('comments/{id}/opinion', 'App\Http\Controllers\Api\V1\Admin\AvisCommentsController@changeOpinion');
        });
    });
});
