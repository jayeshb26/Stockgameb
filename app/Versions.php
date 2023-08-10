<?php
namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Versions extends Model
{
    protected $collection = 'versions';
    protected $primaryKey = '_id';
}
