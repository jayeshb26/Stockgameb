<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Rate extends Model
{
    protected $collection = 'rates';
    protected $primaryKey = '_id';
}
