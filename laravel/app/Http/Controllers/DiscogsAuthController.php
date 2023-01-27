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

class DiscogsAuthController extends Controller
{
    /**
     * @var CacheStorage
     */
    private CacheStorage $storage;

    /**
     * @var ServiceInterface
     */
    private ServiceInterface $bbService;

    /**
     * @return
     * @throws Exception
     */
    public function connect(): Redirector|Application|RedirectResponse
    {
        $this->setupOAuth();

        Cache::flush();

        try {
            $this->storage->retrieveAccessToken('Discogs');

            return redirect('/');
        } catch (TokenNotFoundException $e) {
            $token = $this->bbService->requestRequestToken();
            $url = $this->bbService->getAuthorizationUri(array('oauth_token' => $token->getRequestToken()));

            return redirect($url);
        }
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

        $this->setupOAuth();

        $token = $this->storage->retrieveAccessToken('Discogs');

        $this->bbService->requestAccessToken(
            $request->query('oauth_token'),
            $request->query('oauth_verifier'),
            $token->getRequestTokenSecret()
        );

        // return view
        return redirect('/');
    }

    /**
     * @return void
     * @throws Exception
     */
    private function setupOAuth(): void
    {
        $uriFactory = new UriFactory();
        $currentUri = $uriFactory->createFromSuperGlobalArray($_SERVER);
        $currentUri->setQuery('');

        $serviceFactory = new ServiceFactory();
        $serviceFactory->registerService('Discogs', 'App\Service\OAuth1\Service\Discogs');

        $this->storage = new CacheStorage('discogs_tokens', 'discogs_states');

        // set up the credentials for the requests
        $credentials = new Credentials(
            env('DISCOGS_KEY'),
            env('DISCOGS_SECRET'),
            route('api.discogs.callback')
        );

        $this->bbService = $serviceFactory->createService('Discogs', $credentials, $this->storage);
    }
}
