<?php

namespace App\Models;

use Jenssegers\Mongodb\Relations\BelongsTo;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Relations\EmbedsMany;

/**
 * Class Artist
 * @package App\Models
 */
class Artist extends Eloquent
{
    /**
     * @var string
     */
    protected $collection = 'label';

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'name',
        'anv',
        'join',
        'role',
        'tracks',
        'resource_url'
    ];
}
