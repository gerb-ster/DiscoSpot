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
