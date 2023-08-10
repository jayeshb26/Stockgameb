<?php
namespace App;
use Jenssegers\Mongodb\Eloquent\Model;

class Alert extends Model
{
    protected $collection = 'alerts';
    protected $primaryKey = '_id';
}	
?>
