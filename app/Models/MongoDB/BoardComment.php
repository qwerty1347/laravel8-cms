<?php

namespace App\Models\MongoDB;

use Jenssegers\Mongodb\Eloquent\Model as MongoModel;

class BoardComment extends MongoModel
{
    protected $connection = 'mongodb';
}
