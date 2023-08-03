<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\DiscogsAuthController;
use App\Http\Controllers\DiscogsController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\SpotifyAuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

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

Route::get('/', function () {
    return Inertia::render('LandingPage');
})->name('landingpage');

Route::get('/get-started', function () {
    return Inertia::render('GetStarted');
})->name('get-started');

Route::get('/setup/discogs', function () {
    return Inertia::render('Setup/Discogs');
})->name('app.setup.discogs');

Route::get('/auth/discogs/connect', function () {
    return Socialite::driver('discogs')->redirect();
})->name('auth.discogs.connect');

Route::get('/auth/discogs/callback', [DiscogsAuthController::class, 'callback'])
    ->name('auth.discogs.callback');

// User Restricted Area
Route::group(['middleware' => ['auth']], function () {
    Route::get('/setup/spotify', function () {
        return Inertia::render('Setup/Spotify');
    })->name('app.setup.spotify');

    Route::get('/auth/spotify/connect', function () {
        return Socialite::driver('spotify')->scopes([
            'playlist-read-private',
            'playlist-read-collaborative',
            'playlist-modify-private',
            'playlist-modify-public',
        ])->redirect();
    })->name('auth.spotify.connect');

    Route::get('/auth/spotify/callback', [SpotifyAuthController::class, 'callback'])
        ->name('auth.spotify.callback');

    Route::get('/my-account', [AccountController::class, 'index'])
        ->name('account.index');
    Route::post('/my-account/save', [AccountController::class, 'save'])
        ->name('account.save');
    Route::get('/sign-out', [AccountController::class, 'signOut'])
        ->name('sign-out');

    Route::get('/playlist/list', [PlaylistController::class, 'index'])
        ->name('playlist.index');
    Route::get('/playlist/{playlist}/show', [PlaylistController::class, 'show'])
        ->name('playlist.show');
    Route::get('/playlist/create', [PlaylistController::class, 'create'])
        ->name('playlist.create');
    Route::post('/playlist/store', [PlaylistController::class, 'store'])
        ->name('playlist.store');
    Route::get('/playlist/{playlist}/sync', [PlaylistController::class, 'sync'])
        ->name('playlist.sync');
    Route::delete('/playlist/{playlist}', [PlaylistController::class, 'destroy'])
        ->name('playlist.destroy');


    Route::get('/discogs/get-folders', [DiscogsController::class, 'getFolders'])
        ->name('discogs.get-folders');
    Route::get('/discogs/get-lists', [DiscogsController::class, 'getLists'])
        ->name('discogs.get-lists');
});
