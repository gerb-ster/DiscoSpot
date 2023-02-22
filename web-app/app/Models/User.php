<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

/**
 * Class User
 *
 * @property int $id
 * @property string $uuid
 * @property int $account_type_id
 * @property int $discogs_id
 * @property string $discogs_username
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
 * @property AccountType $account
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
        'account_type_id',
        'uuid',
		'discogs_id',
        'discogs_username',
		'name',
		'email',
		'avatar',
		'discogs_token',
		'discogs_secret',
		'spotify_token',
		'spotify_refresh_token'
	];


    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // before creating, create a unique number
        static::creating(function (User $model) {
            $model->uuid = Str::uuid();
        });
    }

    /**
     * @return HasMany
     */
	public function playlists(): HasMany
    {
		return $this->hasMany(Playlist::class, 'owner_id');
	}

    /**
     * @return BelongsTo
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }
}
