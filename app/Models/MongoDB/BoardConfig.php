<?php

namespace App\Models\MongoDB;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class BoardConfig extends Eloquent
{
    protected $connection = 'mongodb';
}
