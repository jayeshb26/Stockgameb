<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Payments extends Model
{
    protected $collection = 'payments';
    protected $primaryKey = '_id';
}
