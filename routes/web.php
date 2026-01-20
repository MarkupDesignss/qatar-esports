<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\TournamentController;
use App\Http\Controllers\Admin\AboutSectionController;
use App\Http\Controllers\Admin\LogoController;
use App\Http\Controllers\Admin\BannerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ChallengeController;
use App\Http\Controllers\Admin\LiveStreamController;
use App\Http\Controllers\Admin\FeaturedEventController;
use App\Http\Controllers\Admin\MatchHighlightController;
use App\Http\Controllers\Admin\PreviousWorkController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\NewsController;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Qatar Esports APIs running'
    ]);
});



Route::get('/run-storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage link created successfully!';
});

Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');


Route::prefix('admin')->name('admin.')->group(function () {

    // Login
    Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminController::class, 'login']);



    // Forgot Password
    Route::get('/forgot-password', [AdminController::class, 'forgotPasswordForm'])
        ->name('forgot-password.form');

    Route::post('/forgot-password', [AdminController::class, 'sendResetOtp'])
        ->name('forgot-password.send');

    // OTP Verify
    Route::get('/verify-otp', [AdminController::class, 'otpForm'])
        ->name('otp.form');

    Route::post('/verify-otp', [AdminController::class, 'verifyOtp'])
        ->name('otp.verify');

    // Reset Password
    Route::get('/reset-password', [AdminController::class, 'resetPasswordForm'])
        ->name('reset.form');

    Route::post('/reset-password', [AdminController::class, 'resetPassword'])
        ->name('reset.password');
});



Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('dashboard');
    
        Route::get('/contact', [AboutSectionController::class, 'contact'])
        ->name('contact.index');
    Route::delete('/contacts/{contact}', [AboutSectionController::class, 'deleteContact'])->name('contacts.destroy');


    // User
    Route::get('/users', [UserController::class, 'users'])->name('user.index');
    Route::post(
        'user/{id}/toggle-status',
        [UserController::class, 'toggleStatus']
    )->name('user.toggle-status');
    Route::get('/users/{id}/view', [UserController::class, 'viewUser'])
        ->name('users.view');

    // Games
    Route::resource('games', GameController::class);
    Route::post(
        'game/{game}/toggle-status',
        [GameController::class, 'toggleStatus']
    )->name('game.toggle-status');

        // Tournaments CRUD
        Route::resource('tournaments', TournamentController::class);

        // Tournament Custom Actions
        Route::post(
            'tournaments/{id}/toggle-featured',
            [TournamentController::class, 'toggleFeatured']
        )->name('tournaments.toggle-featured');

        Route::post(
            'tournaments/{id}/toggle-visibility',
            [TournamentController::class, 'toggleVisibility']
        )->name('tournaments.toggle-visibility');
        
            // Logo
    Route::get('/logo', [LogoController::class, 'index'])
        ->name('logo.index');

    Route::get('/logo/edit', [LogoController::class, 'edit'])
        ->name('logo.edit');

    Route::post('/logo/update', [LogoController::class, 'update'])
        ->name('logo.update');

    //Banners
    Route::resource('banners', BannerController::class)
        ->names('banners');
        
            // About
    Route::resource('about', AboutSectionController::class);
    
    // Live stream
    Route::resource('livestream', LiveStreamController::class);

        // Partners
    Route::resource('partners', PartnerController::class);
    Route::get('partners', [PartnerController::class, 'index'])->name('partners.index');
    Route::get('partner', [PartnerController::class, 'create'])->name('partner.create');;
    Route::post('partners', [PartnerController::class, 'store'])->name('partners.store');;
    Route::get('partners/{id}', [PartnerController::class, 'edit'])->name('partners.edit');;
    Route::post('partners/{id}', [PartnerController::class, 'update'])->name('partners.update');;
    Route::post('partners/delete/{id}', [PartnerController::class, 'destroy'])->name('partners.destroy');;

    // Events
    Route::resource('events', FeaturedEventController::class);
    Route::patch('events/{event}/status', [FeaturedEventController::class, 'toggleStatus'])
        ->name('events.status');

    // challengs
    Route::get('challenges', [ChallengeController::class, 'index'])->name('challenge.index');
    Route::get('challenge', [ChallengeController::class, 'create'])->name('challenge.create');;
    Route::post('challenge', [ChallengeController::class, 'store'])->name('challenge.store');;
    Route::get('challenge/{id}', [ChallengeController::class, 'edit'])->name('challenge.edit');;
    Route::put('challenge/{id}', [ChallengeController::class, 'update'])->name('challenge.update');;
    Route::delete('challenge/delete/{id}', [ChallengeController::class, 'destroy'])->name('challenge.destroy');;
    
      // News
    Route::get('news', [NewsController::class, 'index'])->name('news.index');
    Route::get('news/create', [NewsController::class, 'create'])->name('news.create');;
    Route::post('news', [NewsController::class, 'store'])->name('news.store');;
    Route::get('news/{id}', [NewsController::class, 'edit'])->name('news.edit');;
    Route::put('news/{id}', [NewsController::class, 'update'])->name('news.update');;
    Route::delete('news/delete/{id}', [NewsController::class, 'destroy'])->name('news.destroy');;


    // Matches
    Route::resource('matches', MatchHighlightController::class);
    
        // Services
    Route::resource('services', ServiceController::class);

    // work
    Route::resource('previous-works', PreviousWorkController::class);

    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});