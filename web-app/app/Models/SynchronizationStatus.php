<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class SynchronizationStatus
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Synchronization[] $synchronizations
 *
 * @package App\Models
 */
class SynchronizationStatus extends Model
{
    const READY_FOR_START = 1;
    const RETRIEVING_DISCOGS_DATA = 2;
    const RETRIEVING_SPOTIFY_DATA = 3;
    const UPDATING_SPOTIFY_PLAYLIST = 4;
    const CLEANING_UP = 5;
    const DONE = 6;

	protected $table = 'synchronization_status';

	protected $fillable = [
		'name'
	];

    /**
     * @return HasMany
     */
	public function synchronizations(): HasMany
    {
		return $this->hasMany(Synchronization::class, 'status_id');
	}
}
