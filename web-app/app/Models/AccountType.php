<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class AccountType
 *
 * @property int $id
 * @property string $name
 *
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class AccountType extends Model
{
    const FREE = 1;
    const BASIC = 2;
    const FULL = 3;

    const MAX_PLAYLIST_FREE = 3;
    const MAX_PLAYLIST_BASIC = 20;

    const MAX_SYNC_INTERVAL_FREE = 60;
    const MAX_SYNC_INTERVAL_BASIC = 5;

    const MAX_TRACKS_PER_PLAYLIST_FREE = 20;
    const MAX_TRACKS_PER_PLAYLIST_BASIC = 100;

	protected $table = 'account_types';

	protected $fillable = [
		'name'
	];

    /**
     * @return HasMany
     */
	public function users(): HasMany
    {
		return $this->hasMany(User::class);
	}
}
