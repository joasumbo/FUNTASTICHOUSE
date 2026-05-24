<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PoiCategory extends Model
{
    protected $fillable = [
        'name_pt',
        'name_en',
        'icon',
    ];

    public function pois(): HasMany
    {
        return $this->hasMany(Poi::class);
    }
}
