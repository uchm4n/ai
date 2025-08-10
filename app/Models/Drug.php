<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Drug extends Model
{
    protected $guarded = [];

    /**
     * A drug belongs to a substance
     */
    public function substance(): BelongsTo
    {
        return $this->belongsTo(Substance::class);
    }
}
