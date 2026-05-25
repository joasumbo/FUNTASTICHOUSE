<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'experience_id',
        'name',
        'email',
        'phone',
        'check_in',
        'check_out',
        'guests',
        'message',
        'status',
        'total_price',
        'viewed_at',
    ];

    protected $casts = [
        'check_in'    => 'date',
        'check_out'   => 'date',
        'total_price' => 'decimal:2',
        'viewed_at'   => 'datetime',
    ];

    public function experience(): BelongsTo
    {
        return $this->belongsTo(Experience::class);
    }
}
