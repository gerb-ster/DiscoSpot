<?php

namespace App\Models;

use Jenssegers\Mongodb\Relations\BelongsTo;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Relations\EmbedsMany;

/**
 * Class Setting
 * @package App\Models
 */
class Setting extends Eloquent
{
    /**
     * @var string
     */
    protected $collection = 'settings';

    /**
     * @var string[]
     */
    protected $fillable = [
        'key',
        'value'
    ];
}
