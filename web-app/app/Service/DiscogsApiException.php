<?php

namespace App\Service;

use Exception;

class DiscogsApiException extends Exception
{
    /**
     * @return static
     */
    public static function tokenRequiredException(): self
    {
        return new static('This endpoint requires authentication. Discogs token is required.');
    }

    /**
     * @return static
     */
    public static function userAgentRequiredException(): self
    {
        return new static('To define userAgent is required.');
    }
}
