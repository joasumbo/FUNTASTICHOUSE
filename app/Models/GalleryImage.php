<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryImage extends Model
{
    protected $fillable = [
        'experience_id',
        'category',
        'filename',
        'alt_pt',
        'alt_en',
        'order',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function experience(): BelongsTo
    {
        return $this->belongsTo(Experience::class);
    }
}
