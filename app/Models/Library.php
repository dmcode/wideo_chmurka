<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Library extends Model
{
    protected $collection = 'libraries';

    protected $fillable = [
        'lid',
        'visibility',
        'thumb',
        'title',
        'number_views',
        'published_at',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function video()
    {
        return $this->embedsOne(Video::class);
    }
}
