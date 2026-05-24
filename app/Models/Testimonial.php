<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'author_name',
        'author_location',
        'content_pt',
        'content_en',
        'rating',
        'active',
        'order',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
