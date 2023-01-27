<?php

namespace App\Models;

use Jenssegers\Mongodb\Relations\BelongsTo;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Relations\EmbedsMany;
use Jenssegers\Mongodb\Relations\EmbedsOne;

/**
 * Class ProcessorJobLog
 * @package App\Models
 */
class Want extends Eloquent
{
    /**
     * @var string
     */
    protected $collection = 'wants';

    /**
     * @var string[]
     */
    protected $dates = ['date_added'];

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'folder',
        'date_added',
        'rating',
        'basic_information',
        'notes'
    ];

    /**
     * @return EmbedsOne
     */
    public function basic_information(): EmbedsOne
    {
        return $this->embedsOne(BasicInformation::class);
    }
}
