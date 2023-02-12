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
        'associated_playlist_id',
        'discogs_data',
        'spotify_data'
    ];
}
