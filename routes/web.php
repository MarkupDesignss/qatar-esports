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
use App\Http\Controllers\Admin\ModeratorController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\DashboardImageController;
use App\Http\Controllers\Admin\PreviousWorkController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\MapController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\NewsTypeController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\TournamentMatchController;
use App\Http\Controllers\Admin\TournamentRegistrationController;
use App\Http\Controllers\Admin\FooterSettingController;
use App\Http\Controllers\Admin\ContactSettingController;
use App\Http\Controllers\Admin\PageSettingController;
use Illuminate\Support\Facades\Artisan;


Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'Qatar Esports APIs running'
    ]);
});

Route::get('/clear-all', function () {
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');

    return 'All caches cleared!';
});


Route::get('/run-storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage link created successfully!';
});

Route::get('/check-storage-link', function () {
    $path = public_path('storage');
    return [
        'exists' => file_exists($path),
        'is_link' => is_link($path),
        'real_path' => realpath($path),
    ];
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

    // Team winner declaration
    Route::post('/team-registrations/{tournamentId}/declare-winner', [TournamentRegistrationController::class, 'declareTeamWinner'])
        ->name('team-registrations.declare-winner');

    Route::delete('/team-registrations/{tournamentId}/reset-winner', [TournamentRegistrationController::class, 'resetTeamWinner'])
        ->name('team-registrations.reset-winner');

    // Team members view
    Route::get('/team-registrations/{teamId}/members', [TournamentRegistrationController::class, 'viewTeamMembers'])
        ->name('team-registrations.members');

    // Remove team member
    Route::delete('/team-registrations/{registrationId}/remove-member', [TournamentRegistrationController::class, 'removeTeamMember'])
        ->name('team-registrations.remove-member');

    // Add team member manually
    Route::get('/team-registrations/{teamId}/add-member', [TournamentRegistrationController::class, 'showAddMemberForm'])
        ->name('team-registrations.add-member-form');

    Route::post('/team-registrations/{teamId}/add-member', [TournamentRegistrationController::class, 'addTeamMember'])
        ->name('team-registrations.add-member');

    // Prize distribution
    Route::get('/team-registrations/{tournamentId}/prize-distribution', [TournamentRegistrationController::class, 'showPrizeDistribution'])
        ->name('team-registrations.prize-distribution');

    Route::post('/team-registrations/{tournamentId}/distribute-prize', [TournamentRegistrationController::class, 'distributePrize'])
        ->name('team-registrations.distribute-prize');
    Route::delete('team-registrations/{tournamentId}/reset-prize', [TournamentRegistrationController::class, 'resetPrize'])
        ->name('team-registrations.reset-prize');

    // Solo winner declaration
    Route::post('/solo-registrations/{tournamentId}/declare-winner', [TournamentRegistrationController::class, 'declareSoloWinner'])
        ->name('solo-registrations.declare-winner');

    Route::delete('/solo-registrations/{tournamentId}/reset-winner', [TournamentRegistrationController::class, 'resetSoloWinner'])
        ->name('solo-registrations.reset-winner');

    // Solo prize distribution
    Route::post('/solo-registrations/{tournamentId}/distribute-prize', [TournamentRegistrationController::class, 'distributeSoloPrize'])
        ->name('solo-registrations.distribute-prize');

    Route::delete('/solo-registrations/{tournamentId}/reset-prize', [TournamentRegistrationController::class, 'resetSoloPrize'])
        ->name('solo-registrations.reset-prize');

    // Delete solo registration
    Route::delete('/solo-registrations/{registrationId}/delete', [TournamentRegistrationController::class, 'deleteSoloRegistration'])
        ->name('solo-registrations.delete');

    Route::patch('solo-registrations/{registrationId}/mark-claimed', [TournamentRegistrationController::class, 'markSoloPrizeClaimed'])
        ->name('solo-registrations.mark-claimed');
        
        // Change captain route
    Route::post('team-registrations/{teamId}/change-captain', [TournamentRegistrationController::class, 'changeCaptain'])
        ->name('team-registrations.change-captain');


    Route::resource('dashboard-images', DashboardImageController::class);

    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/contact', [AboutSectionController::class, 'contact'])
        ->name('contact.index');
    // In routes/web.php
    Route::post('/admin/contacts/bulk-delete', [AboutSectionController::class, 'bulkDelete'])
        ->name('contacts.bulk-delete');
    // Route::patch('/contacts/{contact}/status', function(){
    //      dd("hello");
    // })
    // ->name('contacts.updateStatus');
    Route::patch('/contacts/{contact}/status', [AboutSectionController::class, 'updateStatus'])
        ->name('contacts.updateStatus');
    Route::delete('/contacts/{contact}', [AboutSectionController::class, 'deleteContact'])->name('contacts.destroy');


    // User
    Route::get('/users', [UserController::class, 'users'])->name('user.index');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
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

    Route::get('tournaments/{id}/export-participants', [TournamentController::class, 'exportParticipants'])->name('tournaments.export-participants');

    Route::resource('moderators', ModeratorController::class);
    Route::get('/permissions', [RolePermissionController::class, 'index'])
        ->name('permissions.index');
    Route::post('/permissions', [RolePermissionController::class, 'update'])
        ->name('permissions.update');

    // Logo
    Route::get('/logo', [LogoController::class, 'index'])
        ->name('logo.index');

    Route::get('/logo/edit', [LogoController::class, 'edit'])
        ->name('logo.edit');

    Route::post('/logo/update', [LogoController::class, 'update'])
        ->name('logo.update');
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');

    //Banners
    Route::resource('banners', BannerController::class)
        ->names('banners');

    Route::get('about/edit-main', [AboutSectionController::class, 'editMain'])->name('about.edit-main');
    Route::put('about/update-main', [AboutSectionController::class, 'updateMain'])->name('about.update-main');

    // About
    Route::resource('about', AboutSectionController::class)->except(['show']);

    // Footer Settings
    Route::get('footer', [FooterSettingController::class, 'index'])->name('footer.index');
    Route::get('footer/edit', [FooterSettingController::class, 'edit'])->name('footer.edit');
    Route::put('footer/update', [FooterSettingController::class, 'update'])->name('footer.update');

    // Contact Settings
    Route::get('contact-settings', [ContactSettingController::class, 'index'])->name('contact-settings.index');
    Route::get('contact-settings/edit', [ContactSettingController::class, 'edit'])->name('contact-settings.edit');
    Route::put('contact-settings/update', [ContactSettingController::class, 'update'])->name('contact-settings.update');

    // Live stream
    Route::resource('livestream', LiveStreamController::class)->except(['show']);

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
    Route::get('challenge', [ChallengeController::class, 'create'])->name('challenge.create');
    Route::post('challenge', [ChallengeController::class, 'store'])->name('challenge.store');
    Route::get('challenge/{id}', [ChallengeController::class, 'edit'])->name('challenge.edit');
    Route::put('challenge/{id}', [ChallengeController::class, 'update'])->name('challenge.update');
    Route::delete('challenge/delete/{id}', [ChallengeController::class, 'destroy'])->name('challenge.destroy');

    // Legal Pages (Privacy & Terms)
    Route::get('pages', [PageSettingController::class, 'index'])->name('pages.index');
    Route::get('pages/{slug}/edit', [PageSettingController::class, 'edit'])->name('pages.edit');
    Route::put('pages/{id}', [PageSettingController::class, 'update'])->name('pages.update');

    // Resource route for news types (CRUD)
    Route::resource('news-types', NewsTypeController::class);

    // News
    Route::get('news', [NewsController::class, 'index'])->name('news.index');
    Route::get('news/create', [NewsController::class, 'create'])->name('news.create');;
    Route::post('news', [NewsController::class, 'store'])->name('news.store');;
    Route::get('news/{id}', [NewsController::class, 'edit'])->name('news.edit');;
    Route::put('news/{id}', [NewsController::class, 'update'])->name('news.update');;
    Route::delete('news/delete/{id}', [NewsController::class, 'destroy'])->name('news.destroy');;

    // Tournament Registrations
    Route::get('tournament-registrations/solo', [TournamentRegistrationController::class, 'soloIndex'])->name('tournament-registrations.solo');
    Route::get('tournament-registrations/solo/{tournamentId}', [TournamentRegistrationController::class, 'soloDetail'])->name('tournament-registrations.solo-detail');
    Route::get('tournament-registrations/team', [TournamentRegistrationController::class, 'teamIndex'])->name('tournament-registrations.team');
    Route::get('tournament-registrations/team/{tournamentId}', [TournamentRegistrationController::class, 'teamDetail'])->name('tournament-registrations.team-detail');
    Route::get('tournament-registrations/{id}', [TournamentRegistrationController::class, 'show'])->name('tournament-registrations.show');
    Route::get(
        'teams/{id}/toggle-status',
        [TournamentRegistrationController::class, 'toggleStatus']
    )->name('team.toggle-status');

    Route::get('/solo-registrations/export', [TournamentRegistrationController::class, 'exportSoloCsv'])
        ->name('solo-registrations.export');

    Route::get('/team-registrations/export', [TournamentRegistrationController::class, 'exportTeamCsv'])
        ->name('team-registrations.export');


    // Matches
    Route::resource('matches', MatchHighlightController::class);

    // Services
    Route::resource('services', ServiceController::class);

    // work
    Route::resource('previous-works', PreviousWorkController::class);

    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    Route::get('livestream/tournaments', [LiveStreamController::class, 'getTournaments'])->name('livestream.tournaments');

    // View matches (round-wise)
    Route::get(
        '{tournament}/matches',
        [TournamentMatchController::class, 'index']
    )->name('match.index');

    Route::get(
        '{tournament}/matches/create',
        [TournamentMatchController::class, 'create']
    )->name('match.create');

    Route::post(
        '{tournament}/matches',
        [TournamentMatchController::class, 'store']
    )->name('match.store');
    Route::prefix('tournament/{tournamentId}/matches')->group(function () {

        Route::get('/{matchId}/edit', [TournamentMatchController::class, 'edit'])
            ->name('match.edit');

        Route::put('/{matchId}', [TournamentMatchController::class, 'update'])
            ->name('match.update');

        Route::delete('/{matchId}', [TournamentMatchController::class, 'destroy'])
            ->name('match.destroy');
    });

    Route::post(
        'map-result/{mapId}',
        [TournamentMatchController::class, 'updateMapResult']
    )->name('map.result');

    Route::post(
        'matches/{match}/winner',
        [TournamentMatchController::class, 'updateWinner']
    )->name('match.winner');
    Route::put('match/{match}/details', [TournamentMatchController::class, 'updateDetails'])
        ->name('match.details');

    Route::resource('maps', MapController::class);
});