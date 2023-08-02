<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\BelongsTo;

/**
 * Class SpotifyTrack
 *
 * @property int $id
 * @property int $synchronization_uuid
 * @property string $type
 * @property string $track_uri
 *
 * @package App\Models
 */
class SpotifyTrack extends Model
{
    /**
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * @var string
     */
    protected $collection = 'spotify_tracks';

    /**
     * @var string[]
     */
    protected $dates = ['created_at'];

    /**
     * @var string[]
     */
    protected $fillable = [
        'track_uri',
        'type',
        'synchronization_uuid'
    ];
}
