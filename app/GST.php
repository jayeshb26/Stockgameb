<?php
namespace App;
use Jenssegers\Mongodb\Eloquent\Model;

class GST extends Model
{
    protected $collection = 'gstnumber';
    protected $primaryKey = '_id';
}
?>
