<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['slug', 'title_pt', 'title_en', 'content_pt', 'content_en', 'active'];

    protected $casts = ['active' => 'boolean'];
}
