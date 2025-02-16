<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Embedding extends Model
{
	protected $guarded = [];
    //

	protected $casts = [
		'embeddings' => 'array', // Cast to array automatically
	];
}
