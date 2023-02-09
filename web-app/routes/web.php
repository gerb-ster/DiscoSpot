<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscogsAuthController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\SpotifyAuthController;
use App\Models\User;
use App\Service\DiscogsApiClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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
    return view('welcome');
})->name('welcome');

Route::get('/setup/discogs', [DiscogsAuthController::class, 'setup'])
    ->name('app.setup.discogs');
Route::get('/auth/discogs/connect', function () {
    return Socialite::driver('discogs')->redirect();
})->name('auth.discogs.connect');
Route::get('/auth/discogs/callback', [DiscogsAuthController::class, 'callback'])
    ->name('auth.discogs.callback');

// User Restricted Area
Route::group(['middleware' => ['auth']], function () {
    Route::get('/setup/spotify', [SpotifyAuthController::class, 'setup'])
        ->name('app.setup.spotify');
    Route::get('/auth/spotify/connect', function () {
        return Socialite::driver('spotify')->redirect();
    })->name('auth.spotify.connect');
    Route::get('/auth/spotify/callback', [SpotifyAuthController::class, 'callback'])
        ->name('auth.spotify.callback');

    Route::get('/account/settings', [AccountController::class, 'settings'])
        ->name('account.settings');
    Route::get('/account/sign-out', [AccountController::class, 'signOut'])
        ->name('account.sign-out');

    Route::get('/app/dashboard', [DashboardController::class, 'index'])
        ->name('app.dashboard');

    Route::get('/app/playlist', [PlaylistController::class, 'index'])
        ->name('app.playlist');
});
