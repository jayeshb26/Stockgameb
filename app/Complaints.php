<?php
namespace App;
use Jenssegers\Mongodb\Eloquent\Model;

class Complaints extends Model
{
    protected $collection = 'complaints';
    protected $primaryKey = '_id';
}	
?>
