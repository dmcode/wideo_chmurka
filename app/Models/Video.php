<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Video extends Model
{
//    use HasFactory;
//    use HasUuids;

//    public function uniqueIds()
//    {
//        return ['vid'];
//    }
    protected $collection = 'videos';
}
