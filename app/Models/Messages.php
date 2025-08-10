<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Messages extends Model
{
    protected $fillable = ['text'];

    // belongs to user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
