<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Substance extends Model
{
	protected $guarded = [];

	/**
	 * A substance has many drugs
	 * @return HasMany
	 */
	public function drugs(): HasMany
	{
		return $this->hasMany(Drug::class);
	}
}
