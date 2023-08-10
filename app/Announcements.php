<?php
namespace App;
use Jenssegers\Mongodb\Eloquent\Model;

class Announcements extends Model
{
    protected $collection = 'announcements';
    protected $primaryKey = '_id';
}	
?>
