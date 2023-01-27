<?php

namespace App\Http\Controllers;

use App\Service\Storage\CacheStorage;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use OAuth\Common\Consumer\Credentials;
use OAuth\Common\Exception\Exception;
use OAuth\Common\Http\Uri\UriFactory;
use OAuth\Common\Service\ServiceInterface;
use OAuth\Common\Storage\Exception\TokenNotFoundException;

use OAuth\ServiceFactory;

class SpotifyAuthController extends Controller
{
    /**
     * @return
     * @throws Exception
     */
    public function connect(): Redirector|Application|RedirectResponse
    {

    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     * @throws TokenNotFoundException
     * @throws \Exception
     */
    public function callback(Request $request): Redirector|RedirectResponse|Application
    {
        if (!$request->has('oauth_token')) {
            throw new \Exception('missing oauth_token!');
        }

        // return view
        return redirect('/');
    }
}
