<?php

namespace App\Models;

use Jenssegers\Mongodb\Relations\BelongsTo;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Relations\EmbedsMany;

/**
 * Class Folder
 * @package App\Models
 */
class Folder extends Eloquent
{
    protected $primaryKey = 'id';

    /**
     * @var string
     */
    protected $collection = 'folders';

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'name',
        'count'
    ];
}
