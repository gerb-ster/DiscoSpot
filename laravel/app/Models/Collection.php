<?php

namespace App\Models;

use Jenssegers\Mongodb\Relations\BelongsTo;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Relations\EmbedsMany;

/**
 * Class Collection
 * @package App\Models
 */
class Collection extends Eloquent
{
    /**
     * @var string
     */
    protected $collection = 'collections';

    /**
     * @var string[]
     */
    protected $fillable = [
        'username',
        'releases'
    ];

    /**
     * @return EmbedsMany
     */
    public function releases(): EmbedsMany
    {
        return $this->embedsMany(Release::class);
    }
}
