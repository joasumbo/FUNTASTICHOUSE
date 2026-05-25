<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    public $timestamps  = false;
    public $incrementing = true;

    protected $fillable = [
        'path', 'page_name', 'session_id',
        'ip', 'referrer', 'source', 'device', 'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
