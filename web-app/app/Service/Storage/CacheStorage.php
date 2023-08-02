<?php

namespace App\Service\Storage;

use Illuminate\Support\Facades\Cache;
use OAuth\Common\Storage\Exception\AuthorizationStateNotFoundException;
use OAuth\Common\Storage\Exception\TokenNotFoundException;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\Common\Token\TokenInterface;

class CacheStorage implements TokenStorageInterface
{
    /**
     * @var string
     */
    protected string $key;

    /**
     * @var string
     */
    protected string $stateKey;

    /**
     * @var object|TokenInterface
     */
    protected TokenInterface|array $cachedTokens;

    /**
     * @var object
     */
    protected array|object $cachedStates;

    /**
     * @param string $key The key to store the token under in redis
     * @param string $stateKey the key to store the state under in redis
     */
    public function __construct(string $key, string $stateKey)
    {
        $this->key = $key;
        $this->stateKey = $stateKey;

        $this->cachedTokens = [];
        $this->cachedStates = [];
    }

    /**
     * {@inheritdoc}
     * @throws TokenNotFoundException
     */
    public function retrieveAccessToken($service)
    {
        if (!$this->hasAccessToken($service)) {
            throw new TokenNotFoundException('Token not found in cache');
        }

        if (isset($this->cachedTokens[$service])) {
            return $this->cachedTokens[$service];
        }

        $this->cachedTokens = Cache::get($this->key);

        return $this->cachedTokens[$service];
    }

    /**
     * {@inheritdoc}
     */
    public function storeAccessToken($service, TokenInterface $token): TokenStorageInterface|static
    {
        // (over)write the token
        $this->cachedTokens[$service] = $token;

        Cache::put($this->key, $this->cachedTokens);

        // allow chaining
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasAccessToken($service): bool
    {
        if (isset($this->cachedTokens[$service])
            && $this->cachedTokens[$service] instanceof TokenInterface
        ) {
            return true;
        }

        if (!Cache::has($this->key)) {
            return false;
        }

        $this->cachedTokens = Cache::get($this->key);

        return array_key_exists($service, $this->cachedTokens);
    }

    /**
     * {@inheritdoc}
     */
    public function clearToken($service): TokenStorageInterface|static
    {
        unset($this->cachedTokens[$service]);
        Cache::put($this->key, $this->cachedTokens);

        // allow chaining
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function clearAllTokens(): TokenStorageInterface|static
    {
        // memory
        $this->cachedTokens = [];

        // Cache
        Cache::forget($this->key);

        // allow chaining
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws AuthorizationStateNotFoundException
     */
    public function retrieveAuthorizationState($service)
    {
        if (!$this->hasAuthorizationState($service)) {
            throw new AuthorizationStateNotFoundException('State not found in redis');
        }

        if (isset($this->cachedStates[$service])) {
            return $this->cachedStates[$service];
        }

        $this->cachedStates = Cache::get($this->stateKey);

        return $this->cachedStates[$service];
    }

    /**
     * {@inheritdoc}
     */
    public function storeAuthorizationState($service, $state): TokenStorageInterface|static
    {
        // (over)write the token
        $this->cachedStates[$service] = $state;

        Cache::put($this->stateKey, $this->cachedStates);

        // allow chaining
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasAuthorizationState($service): bool
    {
        if (isset($this->cachedStates[$service]) && !is_null($this->cachedStates[$service])) {
            return true;
        }

        if (!Cache::has($this->stateKey)) {
            return false;
        }

        $this->cachedStates = Cache::get($this->stateKey);

        return array_key_exists($service, $this->cachedStates);
    }

    /**
     * {@inheritdoc}
     */
    public function clearAuthorizationState($service): TokenStorageInterface|static
    {
        unset($this->cachedStates[$service]);
        Cache::put($this->stateKey, $this->cachedStates);

        // allow chaining
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function clearAllAuthorizationStates(): TokenStorageInterface|static
    {
        // memory
        $this->cachedStates = [];

        // Cache
        Cache::forget($this->stateKey);

        // allow chaining
        return $this;
    }

    /**
     * @return string $key
     */
    public function getKey(): string
    {
        return $this->key;
    }
}
