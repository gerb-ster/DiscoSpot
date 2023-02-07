<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class ExternalType
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class PlaylistType extends Model
{
    const BASED_ON_FOLDER = 1;
    const BASED_ON_LIST = 2;
    const BASED_ON_GENRE = 3;

    /**
     * @var string
     */
    protected $table = 'playlist_types';

    protected $fillable = [
        'name'
    ];
}
