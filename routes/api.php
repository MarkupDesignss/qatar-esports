<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TournamentController;
use App\Http\Controllers\API\TournamentRegistrationController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\AboutSectionApiController;
use App\Http\Controllers\API\ContactUsController;
use App\Http\Controllers\API\UserSocialLinkController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\UserProfileController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::get('/logos', [HomeController::class, 'logo']);
Route::get('/banners', [HomeController::class, 'banners']);
Route::get('/news', [NewsController::class, 'newsList']);
Route::get('/login', function () {
    return response()->json(['success' => false, 'message' => 'Authentication token is require to access this api.'], 401);
})->name('login');


/*
|--------------------------------------------------------------------------
| Protected Routes (Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
});


    Route::get('/logo', [HomeController::class, 'logo']);
    Route::get('/banner', [HomeController::class, 'banners']);
    Route::get('/match-highlights', [HomeController::class, 'matchHighlights']);
    Route::get('/match-highlights/{id}', [HomeController::class, 'apiMatchHighlightsShow']);

    Route::get('/welcome', [HomeController::class, 'welcomeSection']);
    Route::get('/featured-events', [HomeController::class, 'featuredEvents']);
    Route::get('/services', [HomeController::class, 'services']);
    Route::get('/events-by-prize', [HomeController::class, 'eventsByPrize']);
    Route::get('/challenge', [HomeController::class, 'challenge']);
    Route::get('/previous-work', [HomeController::class, 'previousWork']);
    Route::get('/partners', [HomeController::class, 'partners']);
    Route::get('/games', [HomeController::class, 'filterGames']);
    Route::get('/LiveGames', [HomeController::class, 'popularLiveGames']);
    Route::get('/popular-live-streams', [HomeController::class, 'popularLiveStreams']);
    Route::get('/featured-tournaments', [HomeController::class, 'featuredTournaments']);
    Route::get('/tournaments', [TournamentController::class, 'index']);
    Route::get('/event-tournaments', [TournamentController::class, 'listByDate']);

    Route::get('/tournaments/search', [TournamentController::class, 'search']);
    Route::get('/tournaments/{id}', [TournamentController::class, 'show']);
        Route::get('/about-sections', [AboutSectionApiController::class, 'index']);

    
    Route::middleware('auth:sanctum')->group(function () {
    Route::get('my-tournaments', [TournamentRegistrationController::class, 'myTournaments']);
    Route::get('my-teams', [TournamentRegistrationController::class, 'myTeams']);
    Route::get('my-history', [TournamentRegistrationController::class, 'myHistory']);
    
    // Social links
    // GET social links
    Route::get('/user/social-links',
        [UserSocialLinkController::class, 'getSocialLinks']
    );

    // CREATE + UPDATE (single API)
    Route::post('/user/social-links',
        [UserSocialLinkController::class, 'saveSocialLinks']
    );
//         Route::post(
//     '/team/members',
//     [TournamentRegistrationController::class, 'teamMembers']
// );
    // Update
    Route::post(
        '/user/profile/update',
        [UserProfileController::class, 'update']
    );
    Route::get('/user/profile', [UserProfileController::class, 'show']);
    

// Route::get('/news', [NewsController::class, 'newsList']);
    Route::post('/news/{id}/like', [NewsController::class, 'toggleLike']);
    Route::post('/news/{id}/bookmark', [NewsController::class, 'toggleBookmark']);

});

   
Route::middleware('auth:sanctum')->prefix('tournaments')->group(function () {
    Route::post('{id}/register/solo', [TournamentRegistrationController::class, 'soloRegister']);
    Route::post('{id}/register/team', [TournamentRegistrationController::class, 'teamRegister']);
    Route::get('{id}/invite-link', [TournamentRegistrationController::class, 'generateInviteLink']);
    Route::post('join-team', [TournamentRegistrationController::class, 'joinTeam']);
    
});

Route::post('/contact-us', [ContactUsController::class, 'store']);