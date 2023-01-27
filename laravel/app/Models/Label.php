<?php

namespace App\Models;

use Jenssegers\Mongodb\Relations\BelongsTo;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Relations\EmbedsMany;

/**
 * Class Label
 * @package App\Models
 */
class Label extends Eloquent
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
        'catno',
        'entity_type',
        'entity_type_name',
        'resource_url'
    ];
}
