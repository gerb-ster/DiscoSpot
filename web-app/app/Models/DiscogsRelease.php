<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\BelongsTo;

/**
 * Class DiscogsRelease
 *
 * @property int $id
 * @property int $synchronization_uuid
 * @property string $master_id
 * @property string $title
 * @property string $artist
 *
 * @package App\Models
 */
class DiscogsRelease extends Model
{
    /**
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * @var string
     */
    protected $collection = 'discogs_releases';

    /**
     * @var string[]
     */
    protected $dates = ['created_at'];

    /**
     * @var string[]
     */
    protected $fillable = [
        'master_id',
        'artist',
        'title',
        'synchronization_uuid'
    ];
}
