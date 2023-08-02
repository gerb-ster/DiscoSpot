<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use App\Models\User;
use App\Service\DiscogsApiClient;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use OAuth\Common\Storage\Exception\TokenNotFoundException;

class DiscogsAuthController extends Controller
{
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
            'discogs_username' => $discogsProfile['username'],
            'name' => $discogsProfile['name'],
            'email' => $discogsProfile['email'],
            'avatar' => $discogsProfile['avatar_url'],
            'discogs_token' => $discogsUser->token,
            'discogs_secret' => $discogsUser->tokenSecret,
            'account_type_id' => AccountType::FULL
        ]);

        Auth::login($user);

        if(is_null($user->spotify_token) && is_null($user->spotify_refresh_token)) {
            return redirect(route('app.setup.spotify'));
        }

        return redirect(route('playlist.index'));
    }
}
