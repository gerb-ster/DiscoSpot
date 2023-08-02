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
    const CREATED = 1;
    const RUNNING = 2;
    const FINISHED_WITH_SUCCESS = 3;
    const FINISHED_WITH_FAILURE = 4;

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
