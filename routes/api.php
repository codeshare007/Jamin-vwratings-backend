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

    Route::group(['middleware' => 'api', 'prefix' => 'admin/auth'], function () {
        Route::post('login', 'App\Http\Controllers\Api\V1\Admin\AuthController@login');
    });

    // Authenticated methods
    Route::middleware('auth:api')->group(function () {

        Route::get('comments', 'App\Http\Controllers\Api\V1\Front\SiteController@comments');
        Route::get('claimed', 'App\Http\Controllers\Api\V1\Front\ClaimsController@index');
        Route::post('stay-claimed', 'App\Http\Controllers\Api\V1\Front\ClaimsController@stayClaimed');
        Route::post('claim', 'App\Http\Controllers\Api\V1\Front\ClaimsController@claim');

        Route::prefix('auth')->group(function () {
            Route::post('logout', 'App\Http\Controllers\Api\V1\Front\AuthController@logout');
            Route::post('refresh', 'App\Http\Controllers\Api\V1\Front\AuthController@refresh');
            Route::get('me', 'App\Http\Controllers\Api\V1\Front\AuthController@me');
        });

        // Admin methods
        Route::prefix('admin')->group(function () {

            Route::prefix('auth')->group(function () {
                Route::post('logout', 'App\Http\Controllers\Api\V1\Admin\AuthController@logout');
                Route::post('refresh', 'App\Http\Controllers\Api\V1\Admin\AuthController@refresh');
                Route::get('me', 'App\Http\Controllers\Api\V1\Admin\AuthController@me');
            });

            Route::get('dashboard', 'App\Http\Controllers\Api\V1\Admin\AdminController@dashboard');
            Route::get('dashboard/hits', 'App\Http\Controllers\Api\V1\Admin\AdminController@getHits');
            Route::post('dashboard/hits', 'App\Http\Controllers\Api\V1\Admin\AdminController@changeHits');

            Route::resource('users', 'App\Http\Controllers\Api\V1\Admin\UsersController');
            Route::resource('messages', 'App\Http\Controllers\Api\V1\Admin\MessagesController');
            Route::resource('campaigns', 'App\Http\Controllers\Api\V1\Admin\AdsCampaignsController');

            // Avis
            Route::resource('avis', 'App\Http\Controllers\Api\V1\Admin\AvisController')
                ->names('admin.avis');

            Route::resource('avis-claims', 'App\Http\Controllers\Api\V1\Admin\AvisClaimsController');
            Route::resource('avis-comments', 'App\Http\Controllers\Api\V1\Admin\AvisCommentsController');
            Route::resource('avis-ratings', 'App\Http\Controllers\Api\V1\Admin\AvisRatingsController');

            Route::resource('parties', 'App\Http\Controllers\Api\V1\Admin\PartiesController')
                ->names('admin.parties');

            Route::resource('parties-claims', 'App\Http\Controllers\Api\V1\Admin\PartiesClaimsController');
            Route::resource('parties-comments', 'App\Http\Controllers\Api\V1\Admin\PartiesCommentsController');
            Route::resource('parties-ratings', 'App\Http\Controllers\Api\V1\Admin\PartiesRatingsController');

            Route::post('users/bulk-delete', 'App\Http\Controllers\Api\V1\Admin\UsersController@bulkDelete');
            Route::post('messages/bulk-delete', 'App\Http\Controllers\Api\V1\Admin\MessagesController@bulkDelete');

            Route::post('avis/bulk-delete', 'App\Http\Controllers\Api\V1\Admin\AvisController@bulkDelete');
            Route::post('avis-claims/bulk-delete', 'App\Http\Controllers\Api\V1\Admin\AvisClaimsController@bulkDelete');
            Route::post('avis-ratings/bulk-delete', 'App\Http\Controllers\Api\V1\Admin\AvisRatingsController@bulkDelete');
            Route::post('avis-comments/bulk-delete', 'App\Http\Controllers\Api\V1\Admin\AvisCommentsController@bulkDelete');
            Route::post('avis-comments/bulk-opinion', 'App\Http\Controllers\Api\V1\Admin\AvisCommentsController@bulkOpinion');
            Route::post('avis-comments/{id}/opinion', 'App\Http\Controllers\Api\V1\Admin\AvisCommentsController@changeOpinion');

            Route::post('parties/bulk-delete', 'App\Http\Controllers\Api\V1\Admin\PartiesController@bulkDelete');
            Route::post('parties-claims/bulk-delete', 'App\Http\Controllers\Api\V1\Admin\PartiesClaimsController@bulkDelete');
            Route::post('parties-ratings/bulk-delete', 'App\Http\Controllers\Api\V1\Admin\PartiesRatingsController@bulkDelete');
            Route::post('parties-comments/bulk-delete', 'App\Http\Controllers\Api\V1\Admin\PartiesCommentsController@bulkDelete');
            Route::post('parties-comments/bulk-opinion', 'App\Http\Controllers\Api\V1\Admin\PartiesCommentsController@bulkOpinion');
            Route::post('parties-comments/{id}/opinion', 'App\Http\Controllers\Api\V1\Admin\PartiesCommentsController@changeOpinion');
        });
    });
});
