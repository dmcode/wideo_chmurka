<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Video extends Model
{
    protected $collection = 'videos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vid',
        'duration',
        'res_w',
        'res_h',
        'size',
        'codec_name',
        'format_name',
    ];
}
