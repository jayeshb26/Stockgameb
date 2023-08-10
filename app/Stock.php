<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Stock extends Model
{
    protected $collection = 'stocks';
    protected $primaryKey = '_id';
}
