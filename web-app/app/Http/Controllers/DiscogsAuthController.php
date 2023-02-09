<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Service\DiscogsApiClient;
use App\Service\Storage\CacheStorage;
use GuzzleHttp\Exception\GuzzleException;
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

class DiscogsAuthController extends Controller
{
    /**
     * @return View
     */
    public function setup(): View
    {
        // return view
        return view('setup.discogs');
    }

    /**
     * @return Application|RedirectResponse|Redirector
     * @throws TokenNotFoundException
     * @throws \Exception|GuzzleException
     */
    public function callback(): Redirector|RedirectResponse|Application
    {
        $discogsUser = Socialite::driver('discogs')->user();

        $discogsClient = new DiscogsApiClient($discogsUser->token, $discogsUser->tokenSecret);
        $discogsClient->cacheEnabled = false;

        $discogsProfile = $discogsClient->get('users/' . $discogsUser->getNickname());

        $user = User::updateOrCreate([
            'discogs_id' => $discogsUser->getId(),
        ], [
            'name' => $discogsUser->getNickname(),
            'email' => $discogsProfile['email'],
            'avatar' => $discogsProfile['avatar_url'],
            'discogs_token' => $discogsUser->token,
            'discogs_secret' => $discogsUser->tokenSecret
        ]);

        Auth::login($user);

        if(is_null($user->spotify_token) && is_null($user->spotify_refresh_token)) {
            return redirect(route('app.setup.spotify'));
        }

        return redirect(route('app.dashboard'));
    }
}
