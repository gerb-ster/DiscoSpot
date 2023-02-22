<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class Playlist
 *
 * @property int $id
 * @property string $uuid
 * @property int $owner_id
 * @property int $playlist_type_id
 * @property array $discogs_query_data
 * @property string $name
 * @property string $spotify_identifier
 * @property Carbon $last_sync
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property User $owner
 * @property PlaylistType $playlistType
 *
 * @package App\Models
 */
class Playlist extends Model
{
	protected $table = 'playlists';

	protected $casts = [
		'owner_id' => 'int',
		'playlist_type_id' => 'int',
        'discogs_query_data' => 'array'
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
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        // before creating, create a unique number
        static::creating(function (Playlist $model) {
            $model->owner_id = Auth::user()->id;
            $model->uuid = Str::uuid();
        });
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return Model|null
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        return $this->where('uuid', $value)->firstOrFail();
    }

    /**
     * @return BelongsTo
     */
	public function owner(): BelongsTo
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
