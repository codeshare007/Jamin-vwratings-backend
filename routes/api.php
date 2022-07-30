<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Front\{
    AvisController,
    AuthController,
    SiteController,
    TestController,
    StatisticsController,
    AvisInterviewsController,
    ProfileController,
    PartiesController,
    NominationsController,						  
};

use App\Http\Controllers\Api\V1\Admin\{
    AdminController,
    UsersController,
    UsersNotificationsController as AdminUsersNotificationsController,
    NotificationsController as AdminNotificationsController,
    AuthController as AdminAuthController,
    SettingsController
};

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

    Route::get('test', [TestController::class, 'test']);
    Route::get('statistics', [StatisticsController::class, 'index']);

    Route::post('reset-password', [AuthController::class, 'sendPasswordResetLink']);
    Route::post('reset/password', [AuthController::class, 'callResetPassword']);

    // Public Methods
    Route::post('tracker', [SiteController::class, 'sendTracker']);
    Route::post('end-promo', [SiteController::class, 'endPromo']);
    Route::post('send-message', [SiteController::class, 'message']);
    Route::get('fetch_timer', 'App\Http\Controllers\Api\V1\Front\TimerController@index');
    // Avis
    Route::middleware('auth:api')->group(function () {
        Route::get('avis/interviews', [AvisInterviewsController::class, 'index']);
        Route::get('avis/attachments', [AvisController::class, 'attachments']);
        Route::get('avis/{id}/interview', [AvisController::class, 'getInterview']);
        Route::post('avis/{id}/rate', [AvisController::class, 'rate']);
        Route::post('avis/{id}/comment', [AvisController::class, 'comment']);
        Route::post('avis/{id}/favorite', [AvisController::class, 'favorite']);
        Route::delete('avis/{id}/remove-favorite', [AvisController::class, 'removeFavorite']);
    });

    Route::resource('avis', AvisController::class);

    // Parties
    Route::middleware('auth:api')->group(function () {
        Route::get('parties/attachments', [PartiesController::class, 'attachments']);
        Route::post('parties/{id}/rate', [PartiesController::class, 'rate']);
        Route::post('parties/{id}/comment', [PartiesController::class, 'comment']);
        Route::post('parties/{id}/favorite', [PartiesController::class, 'favorite']);
        Route::delete('parties/{id}/remove-favorite', [PartiesController::class, 'removeFavorite']);
    });

    Route::resource('parties', PartiesController::class);

    // Nominations
    Route::middleware('auth:api')->group(function () {

    });

    Route::resource('nominations', NominationsController::class);
    // Auth methods
    Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
    });

    Route::group(['middleware' => 'api', 'prefix' => 'admin/auth'], function () {
        Route::post('login', [AdminAuthController::class, 'login']);
    });

    // Authenticated methods
    Route::middleware('auth:api')->group(function () {

        Route::get('profile', [ProfileController::class, 'index']);
        Route::post('profile', [ProfileController::class, 'update']);

        Route::post('profile/change-password', [ProfileController::class, 'changePassword']);
        Route::post('profile/notifications/read', [ProfileController::class, 'readAllNotifications']);
        Route::post('profile/notifications/{id}/read', [ProfileController::class, 'readNotification']);

        Route::get('profile/notifications', [ProfileController::class, 'getNotifications']);
        Route::get('profile/avis/favorites', [ProfileController::class, 'favoriteAvis']);
        Route::get('profile/avis/comments', [ProfileController::class, 'commentsAvis']);
        Route::get('profile/avis/stats', [ProfileController::class, 'statsAvis']);

        Route::get('profile/parties/favorites', [ProfileController::class, 'favoriteParties']);
        Route::get('profile/parties/comments', [ProfileController::class, 'commentsParties']);
        Route::get('profile/parties/stats', [ProfileController::class, 'statsParties']);

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
                Route::post('logout', [AdminAuthController::class, 'logout']);
                Route::post('refresh', [AdminAuthController::class, 'refresh']);
                Route::get('me', [AuthController::class, 'me']);
            });

            Route::get('dashboard', [AdminController::class, 'dashboard']);
            Route::get('dashboard/hits', [AdminController::class, 'getHits']);
            Route::post('dashboard/hits', [AdminController::class, 'changeHits']);

            Route::get('settings/announcement', [SettingsController::class, 'getAnnouncement']);
            Route::post('settings/announcement', [SettingsController::class, 'setAnnouncement']);

            Route::resource('notifications', AdminNotificationsController::class);
            Route::resource('users-notifications', AdminUsersNotificationsController::class);

            Route::resource('settings', SettingsController::class);
            Route::resource('users', UsersController::class);
            Route::resource('messages', 'App\Http\Controllers\Api\V1\Admin\MessagesController');
            Route::resource('campaigns', 'App\Http\Controllers\Api\V1\Admin\AdsCampaignsController');

            // Avis
            Route::resource('avis', 'App\Http\Controllers\Api\V1\Admin\AvisController')
                ->names('admin.avis');

            Route::resource('avis-claims', 'App\Http\Controllers\Api\V1\Admin\AvisClaimsController');
            Route::resource('avis-comments', 'App\Http\Controllers\Api\V1\Admin\AvisCommentsController');
            Route::resource('avis-ratings', 'App\Http\Controllers\Api\V1\Admin\AvisRatingsController');
            Route::resource('avis-interviews', 'App\Http\Controllers\Api\V1\Admin\AvisInterviewsController');

            Route::resource('parties', 'App\Http\Controllers\Api\V1\Admin\PartiesController')
                ->names('admin.parties');

            Route::resource('parties-claims', 'App\Http\Controllers\Api\V1\Admin\PartiesClaimsController');
            Route::resource('parties-comments', 'App\Http\Controllers\Api\V1\Admin\PartiesCommentsController');
            Route::resource('parties-ratings', 'App\Http\Controllers\Api\V1\Admin\PartiesRatingsController');

            Route::post('users/bulk-delete', 'App\Http\Controllers\Api\V1\Admin\UsersController@bulkDelete');
            Route::post('messages/bulk-delete', 'App\Http\Controllers\Api\V1\Admin\MessagesController@bulkDelete');
            Route::post('users-notifications/bulk-delete', 'App\Http\Controllers\Api\V1\Admin\UsersNotificationsController@bulkDelete');
            Route::post('notifications/bulk-delete', 'App\Http\Controllers\Api\V1\Admin\NotificationsController@bulkDelete');			  

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
