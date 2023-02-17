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
 * Class Synchronization
 *
 * @property int $id
 * @property string $uuid
 * @property int $playlist_id
 * @property int $status_id
 * @property array $statistics
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Playlist $playlist
 * @property SynchronizationStatus $synchronization_status
 *
 * @package App\Models
 */
class Synchronization extends Model
{
	protected $table = 'synchronizations';

	protected $casts = [
		'playlist_id' => 'int',
		'status_id' => 'int',
        'statistics' => 'array'
	];

	protected $fillable = [
		'uuid',
		'playlist_id',
		'status_id',
		'statistics'
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
        static::creating(function (Synchronization $model) {
            $model->uuid = Str::uuid();
        });
    }

    /**
     * @return BelongsTo
     */
	public function playlist(): BelongsTo
    {
		return $this->belongsTo(Playlist::class);
	}

    /**
     * @return BelongsTo
     */
	public function status(): BelongsTo
    {
		return $this->belongsTo(SynchronizationStatus::class, 'status_id');
	}
}
