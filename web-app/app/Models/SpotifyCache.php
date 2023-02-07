<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SpotifyCache
 * 
 * @property int $id
 * @property int $discogs_master_release_id
 * @property string $spotify_uri
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class SpotifyCache extends Model
{
	protected $table = 'spotify_cache';

	protected $casts = [
		'discogs_master_release_id' => 'int'
	];

	protected $fillable = [
		'discogs_master_release_id',
		'spotify_uri'
	];
}
