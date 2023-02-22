<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use OAuth\Common\Storage\Exception\TokenNotFoundException;

class SpotifyAuthController extends Controller
{
    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     * @throws TokenNotFoundException
     * @throws \Exception
     */
    public function callback(Request $request): Redirector|RedirectResponse|Application
    {
        $spotifyUser = Socialite::driver('spotify')->user();

        $user = Auth::user();

        $user->spotify_token = $spotifyUser->token;
        $user->spotify_refresh_token = $spotifyUser->refreshToken;
        $user->save();

        return redirect(route('playlist.index'));
    }
}
