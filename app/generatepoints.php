<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class generatepoints extends Model
{
    protected $collection = 'admingeneratepoints';
    protected $primaryKey = '_id';
}
