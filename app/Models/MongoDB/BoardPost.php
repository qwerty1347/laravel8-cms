<?php

namespace App\Models\MongoDB;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class BoardPost extends Eloquent
{
    protected $connection = 'mongodb';
}
