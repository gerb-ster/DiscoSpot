<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 *
 * @property int $id
 * @property int $discogs_id
 * @property string $name
 * @property string $email
 * @property string $avatar
 * @property string $discogs_token
 * @property string|null $discogs_secret
 * @property string|null $spotify_token
 * @property string|null $spotify_refresh_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Playlist[] $playlists
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	protected $table = 'users';

	protected $casts = [
		'discogs_id' => 'int'
	];

	protected $hidden = [
		'discogs_token',
		'discogs_secret',
		'spotify_token',
		'spotify_refresh_token'
	];

	protected $fillable = [
		'discogs_id',
		'name',
		'email',
		'avatar',
		'discogs_token',
		'discogs_secret',
		'spotify_token',
		'spotify_refresh_token'
	];

    /**
     * @return HasMany
     */
	public function playlists(): HasMany
    {
		return $this->hasMany(Playlist::class, 'owner_id');
	}
}
