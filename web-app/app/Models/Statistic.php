<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\BelongsTo;

/**
 * Class SpotifyTrack
 *
 * @property int $synchronization_uuid
 * @property string $type
 * @property string $value
 *
 * @package App\Models
 */
class Statistic extends Model
{
    const RELEASE_NOT_FOUND = 'release_not_found';
    const RELEASES_IN_FOLDER = 'releases_in_folder';
    const RELEASES_IN_LIST = 'releases_in_list';
    const RELEASES_IN_WANTLIST = 'releases_in_list';
    const RELEASES_AFTER_FILTER = 'releases_after_filter';
    const TRACKS_ALREADY_IN_PLAYLIST = 'tracks_already_in_playlist';
    const TRACKS_ADDED_TO_PLAYLIST = 'tracks_added_to_playlist';
    const TRACKS_REMOVED_FROM_PLAYLIST = 'tracks_removed_from_playlist';

    /**
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * @var string
     */
    protected $collection = 'statistics';

    /**
     * @var string[]
     */
    protected $dates = ['created_at'];

    /**
     * @var string[]
     */
    protected $fillable = [
        'synchronization_uuid',
        'time_stamp',
        'type',
        'value'
    ];
}
