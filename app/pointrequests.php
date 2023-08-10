<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class pointrequests extends Model
{
    protected $collection = 'pointrequests';
    protected $primaryKey = '_id';
    protected $timestamp = true;
}
