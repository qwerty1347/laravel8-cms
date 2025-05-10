<?php

namespace App\Models\MongoDB;

use Jenssegers\Mongodb\Eloquent\Model as MongoModel;

class BoardConfig extends MongoModel
{
    protected $connection = 'mongodb';
}
