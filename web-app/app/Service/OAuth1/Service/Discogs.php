<?php

namespace App\Service\OAuth1\Service;

use OAuth\Common\Token\TokenInterface as TokenInterfaceAlias;
use OAuth\OAuth1\Service\AbstractService;
use OAuth\OAuth1\Signature\SignatureInterface;
use OAuth\OAuth1\Token\StdOAuth1Token;
use OAuth\Common\Http\Exception\TokenResponseException;
use OAuth\Common\Http\Uri\Uri;
use OAuth\Common\Consumer\CredentialsInterface;
use OAuth\Common\Http\Uri\UriInterface;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\Common\Http\Client\ClientInterface;
use OAuth\OAuth1\Token\TokenInterface;

class Discogs extends AbstractService
{
    /**
     * @param CredentialsInterface $credentials
     * @param ClientInterface $httpClient
     * @param TokenStorageInterface $storage
     * @param SignatureInterface $signature
     * @param UriInterface|null $baseApiUri
     */
    public function __construct(
        CredentialsInterface $credentials,
        ClientInterface $httpClient,
        TokenStorageInterface $storage,
        SignatureInterface $signature,
        UriInterface $baseApiUri = null
    ) {
        parent::__construct($credentials, $httpClient, $storage, $signature, $baseApiUri);

        if (null === $baseApiUri) {
            $this->baseApiUri = new Uri('https://api.discogs.com/');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getRequestTokenEndpoint(): UriInterface|Uri
    {
        return new Uri('https://api.discogs.com/oauth/request_token');
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorizationEndpoint(): UriInterface|Uri
    {
        return new Uri('https://www.discogs.com/oauth/authorize');
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessTokenEndpoint(): UriInterface|Uri
    {
        return new Uri('https://api.discogs.com/oauth/access_token');
    }

    /**
     * {@inheritdoc}
     * @throws TokenResponseException
     */
    protected function parseRequestTokenResponse($responseBody): TokenInterface|StdOAuth1Token
    {
        parse_str($responseBody, $data);

        if (!is_array($data)) {
            throw new TokenResponseException('Unable to parse response.');
        } elseif (!isset($data['oauth_callback_confirmed']) || $data['oauth_callback_confirmed'] !== 'true') {
            throw new TokenResponseException('Error in retrieving token.');
        }

        return $this->parseAccessTokenResponse($responseBody);
    }

    /**
     * {@inheritdoc}
     * @throws TokenResponseException
     */
    protected function parseAccessTokenResponse($responseBody): TokenInterface|StdOAuth1Token
    {
        parse_str($responseBody, $data);

        if (!is_array($data)) {
            throw new TokenResponseException('Unable to parse response.');
        } elseif (isset($data['error'])) {
            throw new TokenResponseException('Error in retrieving token: "' . $data['error'] . '"');
        }

        $token = new StdOAuth1Token();

        $token->setRequestToken($data['oauth_token']);
        $token->setRequestTokenSecret($data['oauth_token_secret']);
        $token->setAccessToken($data['oauth_token']);
        $token->setAccessTokenSecret($data['oauth_token_secret']);

        $token->setEndOfLife(TokenInterfaceAlias::EOL_NEVER_EXPIRES);
        unset($data['oauth_token'], $data['oauth_token_secret']);
        $token->setExtraParams($data);

        return $token;
    }
}
