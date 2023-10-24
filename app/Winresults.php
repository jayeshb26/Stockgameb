<?php
namespace App;
use Jenssegers\Mongodb\Eloquent\Model;

class Winresults extends Model
{
    protected $collection = 'winresults';
    protected $primaryKey = '_id';
}	
?>
