<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Poi extends Model
{
    protected $fillable = [
        'poi_category_id',
        'name_pt',
        'name_en',
        'description_pt',
        'description_en',
        'lat',
        'lng',
        'distance_km',
        'active',
    ];

    protected $casts = [
        'lat'         => 'decimal:7',
        'lng'         => 'decimal:7',
        'distance_km' => 'decimal:1',
        'active'      => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PoiCategory::class, 'poi_category_id');
    }
}
