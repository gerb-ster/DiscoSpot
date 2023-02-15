<?php

namespace App\Models;

use Carbon\Carbon;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\EmbedsMany;

/**
 * Class Synchronization
 *
 * @property int $id
 * @property int $associated_playlist_id
 * @property array $discogs_data
 * @property array $current_spotify_tracks_cache
 *
 * @package App\Models
 */
class Synchronization extends Model
{
    const STATUS_READY_FOR_START = 1;
    const STATUS_RETRIEVING_DISCOGS_DATA = 2;
    const STATUS_RETRIEVING_SPOTIFY_DATA = 3;
    const STATUS_UPDATING_SPOTIFY_PLAYLIST = 4;
    const STATUS_DONE = 5;

    /**
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * @var string
     */
    protected $collection = 'synchronizations';

    /**
     * @var string[]
     */
    protected $dates = ['created_at'];

    /**
     * @var string[]
     */
    protected $fillable = [
        'status_id',
        'associated_playlist_id',
        'discogs_data',
        'current_spotify_tracks_cache'
    ];
}
