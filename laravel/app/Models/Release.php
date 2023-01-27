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
class Release extends Eloquent
{
    /**
     * @var string
     */
    protected $collection = 'releases';

    /**
     * @var string[]
     */
    protected $dates = ['date_added'];

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'folder_id',
        'instance_id',
        'date_added',
        'rating',
        'basic_information'
    ];

    /**
     * @return EmbedsOne
     */
    public function basic_information(): EmbedsOne
    {
        return $this->embedsOne(BasicInformation::class);
    }

    /**
     * @return EmbedsOne
     */
    public function folder(): EmbedsOne
    {
        return $this->embedsOne(Folder::class);
    }

    /**
     * @return BelongsTo
     */
    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }
}
