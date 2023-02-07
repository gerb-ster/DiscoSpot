<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Playlist
 *
 * @property int $id
 * @property string $uuid
 * @property int $owner_id
 * @property int $playlist_type_id
 * @property string $discogs_query_data
 * @property string $name
 * @property string $spotify_identifier
 * @property Carbon $last_sync
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property User $user
 * @property PlaylistType $playlistType
 *
 * @package App\Models
 */
class Playlist extends Model
{
	protected $table = 'playlists';

	protected $casts = [
		'owner_id' => 'int',
		'playlist_type_id' => 'int'
	];

	protected $dates = [
		'last_sync'
	];

	protected $fillable = [
		'uuid',
		'owner_id',
		'playlist_type_id',
		'discogs_query_data',
		'name',
		'spotify_identifier',
		'last_sync'
	];

    /**
     * @return BelongsTo
     */
	public function user(): BelongsTo
    {
		return $this->belongsTo(User::class, 'owner_id');
	}

    /**
     * @return BelongsTo
     */
	public function playlistType(): BelongsTo
    {
		return $this->belongsTo(PlaylistType::class);
	}
}
