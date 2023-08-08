<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Throwable;

/**
 * Class Synchronization
 *
 * @property int $id
 * @property string $uuid
 * @property int $playlist_id
 * @property int $status_id
 * @property array $feedback
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
        'statistics' => 'array',
        'feedback' => 'array'
	];

	protected $fillable = [
		'uuid',
		'playlist_id',
		'status_id',
		'statistics',
        'feedback'
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
            $model->status_id = SynchronizationStatus::CREATED;
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

    /**
     * @param bool $isSuccess
     * @param Throwable|null $exception
     * @return void
     */
    public function finish(bool $isSuccess, Throwable $exception = null): void
    {
        // finish the Synchronization
        $this->status_id = $isSuccess ? SynchronizationStatus::FINISHED_WITH_SUCCESS : SynchronizationStatus::FINISHED_WITH_FAILURE;

        if (!$isSuccess) {
            $this->addThrowableToFeedback($exception, false);
        }

        $this->save();

        // set the sync bit on the assoc. playlist
        $this->playlist->update([
            'is_synchronizing' => false
        ]);
    }

    public function addThrowableToFeedback(Throwable $exception, bool $save = true): void
    {
        $this->feedback[] = [
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ];

        if ($save) {
            $this->save();
        }
    }
}
