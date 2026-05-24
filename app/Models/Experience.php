<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Experience extends Model
{
    protected $fillable = [
        'slug',
        'name_pt',
        'name_en',
        'description_pt',
        'description_en',
        'short_description_pt',
        'short_description_en',
        'base_price',
        'weekend_price',
        'max_guests',
        'bedrooms',
        'active',
    ];

    protected $casts = [
        'base_price'     => 'decimal:2',
        'weekend_price'  => 'decimal:2',
        'active'         => 'boolean',
    ];

    public function pricingRules(): HasMany
    {
        return $this->hasMany(PricingRule::class);
    }

    public function blockedDates(): HasMany
    {
        return $this->hasMany(BlockedDate::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(GalleryImage::class);
    }
}
