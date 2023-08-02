<?php

namespace App\Service;

use App\Service\Storage\CacheStorage;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Illuminate\Support\Facades\Cache;
use OAuth\Common\Storage\Exception\TokenNotFoundException;
use Psr\Http\Message\ResponseInterface;

class DiscogsApiClient
{
    /**
     * @var string
     */
    protected string $baseUrl = 'https://api.discogs.com';

    /**
     * @var Client
     */
    protected Client $client;

    /**
     * @var string
     */
    protected string $userAgent = 'disco-spot';

    /**
     */
    public function __construct(string $token, string $tokenSecret)
    {
        $middleware = new Oauth1([
            'consumer_key' => env('DISCOGS_KEY'), // from Discogs developer page
            'consumer_secret' => env('DISCOGS_SECRET'), // from Discogs developer page
            'token' => $token, // get this using a OAuth library
            'token_secret'  => $tokenSecret // get this using a OAuth library
        ]);

        $stack = HandlerStack::create();
        $stack->push($middleware);

        $this->client = new Client([
            'headers' => ['User-Agent' => $this->userAgent],
            'handler' => $stack,
            'auth' => 'oauth'
        ]);
    }

    /**
     * @param string $resource
     * @param array $query
     * @param bool $mustAuthenticate
     * @param bool $cacheEnabled
     * @return mixed
     * @throws DiscogsApiException
     * @throws GuzzleException
     */
    public function get(string $resource, array $query = [], bool $mustAuthenticate = false, bool $cacheEnabled = false): mixed
    {
        $argsHash = hash('sha256', serialize(func_get_args()));

        if ($cacheEnabled && Cache::has($argsHash)) {
           return json_decode(Cache::get($argsHash), true);
        }

        $content = $this->client
            ->get(
                $this->url($this->path($resource)),
                $this->parameters($query, $mustAuthenticate)
            )->getBody()
            ->getContents();

        if ($cacheEnabled) {
            Cache::set($argsHash, $content, 60);
        }

        return json_decode($content, true);
    }

    /**
     * @throws DiscogsApiException
     */
    public function post(string $resource, string $id = '', array $query = [], bool $mustAuthenticate = true): bool|ResponseInterface
    {
        try {
            return $this->client
                ->post(
                    $this->url($this->path($resource, $id)),
                    $this->parameters($query, $mustAuthenticate)
                );

        } catch (GuzzleException $exception) {
            return false;
        }
    }

    /**
     * @throws DiscogsApiException
     */
    protected function delete(string $resource, string $listingId): bool|ResponseInterface
    {
        try {
            return $this->client
                ->delete(
                    $this->url($this->path($resource, $listingId)),
                    ['query' => ['token' => $this->token()]]
                );

        } catch (GuzzleException $exception) {
            return false;
        }
    }

    /**
     * @throws DiscogsApiException
     */
    protected function parameters(array $query, bool $mustAuthenticate) : array
    {
        if ($mustAuthenticate) {
            $query['token'] = $this->token();
        }

        return  [
            'stream' => true,
            'headers' => ['User-Agent' => $this->userAgent],
            'query' => $query,
        ];
    }

    /**
     * @param string $path
     * @return string
     */
    protected function url(string $path) : string
    {
        return "{$this->baseUrl}/{$path}";
    }

    /**
     * @param string $resource
     * @param string $id
     * @return string
     */
    protected function path(string $resource, string $id = ''): string
    {
        if (empty($id)) {
            return $resource;
        }

        return "{$resource}/{$id}";
    }
}
