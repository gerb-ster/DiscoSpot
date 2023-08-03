<?php

namespace app\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PlaylistType
 *
 * @property int $id
 * @property string $name
 *
 * @package App\Models
 */
class FilterType extends Model
{
	protected $table = 'filter_types';

	protected $fillable = [
		'name'
	];
}
