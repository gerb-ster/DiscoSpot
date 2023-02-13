<?php

namespace App\Models;

use Carbon\Carbon;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class Synchronization
 *
 * @property int $id
 * @property int $associated_playlist_id
 * @property array $discogs_data
 * @property array $spotify_data
 *
 * @package App\Models
 */
class Synchronization extends Model
{
    const STATUS_READY_FOR_START = 1;
    const STATUS_RETRIEVING_DISCOGS_DATA = 2;
    const STATUS_RETRIEVING_SPOTIFY_DATA = 3;
    const STATUS_UPDATING_SPOTIFY_PLAYLIST = 4;
    const STATUS_DONE = 4;

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
        'spotify_data'
    ];
}
