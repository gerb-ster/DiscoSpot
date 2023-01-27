<?php

use App\Http\Controllers\DiscogsAuthController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\SpotifyAuthController;
use App\Http\Controllers\WantListController;
use Illuminate\Support\Facades\Route;

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

Route::get('/api', function () {
    return view('welcome');
});

Route::get('/api/discogs/connect', [DiscogsAuthController::class, 'connect'])
    ->name('api.discogs.connect');
Route::get('/api/discogs/callback', [DiscogsAuthController::class, 'callback'])
    ->name('api.discogs.callback');

Route::get('/api/spotify/connect', [SpotifyAuthController::class, 'connect'])
    ->name('api.spotify.connect');
Route::get('/api/spotify/callback', [SpotifyAuthController::class, 'callback'])
    ->name('api.spotify.callback');

Route::resource('/api/collection', CollectionController::class);
Route::resource('/api/wantlist', WantListController::class);
