<?php

namespace App\Models;

use Jenssegers\Mongodb\Relations\BelongsTo;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Relations\EmbedsMany;

/**
 * Class WantList
 * @package App\Models
 */
class WantList extends Eloquent
{
    /**
     * @var string
     */
    protected $collection = 'wantlists';

    /**
     * @var string[]
     */
    protected $fillable = [
        'username',
        'wants'
    ];

    /**
     * @return EmbedsMany
     */
    public function wants(): EmbedsMany
    {
        return $this->embedsMany(Want::class);
    }
}
