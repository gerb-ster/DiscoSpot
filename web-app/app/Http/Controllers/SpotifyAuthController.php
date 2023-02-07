<?php

namespace App\Http\Controllers;

use App\Service\Storage\CacheStorage;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Exception\Exception;
use OAuth\Common\Http\Uri\UriFactory;
use OAuth\Common\Service\ServiceInterface;
use OAuth\Common\Storage\Exception\TokenNotFoundException;

use OAuth\ServiceFactory;

class SpotifyAuthController extends Controller
{
    /**
     * @return View
     */
    public function setup(): View
    {
        // return view
        return view('setup.spotify', [
        ]);
    }

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

        return redirect(route('app.dashboard'));
    }
}
