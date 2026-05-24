<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PricingRule extends Model
{
    protected $fillable = [
        'experience_id',
        'season',
        'start_date',
        'end_date',
        'price_per_night',
    ];

    protected $casts = [
        'start_date'      => 'date',
        'end_date'        => 'date',
        'price_per_night' => 'decimal:2',
    ];

    public function experience(): BelongsTo
    {
        return $this->belongsTo(Experience::class);
    }
}
