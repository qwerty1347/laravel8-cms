<?php

namespace App\Models\MongoDB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\Model as MongoModel;

class BoardPost extends MongoModel
{
    use HasFactory;
    use SoftDeletes;

    protected $connection = 'mongodb';
    protected $dates = ['deleted_at'];
}
