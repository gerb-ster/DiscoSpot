<?php

namespace App\Models;

use Jenssegers\Mongodb\Relations\BelongsTo;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Relations\EmbedsMany;

/**
 * Class Format
 * @package App\Models
 */
class Format extends Eloquent
{
    /**
     * @var string
     */
    protected $collection = 'format';

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'qty',
        'descriptions'
    ];
}
