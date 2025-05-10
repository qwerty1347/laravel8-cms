<?php

namespace App\Models\MongoDB;

use Jenssegers\Mongodb\Eloquent\Model as MongoModel;

class BoardPost extends MongoModel
{
    protected $connection = 'mongodb';
}
