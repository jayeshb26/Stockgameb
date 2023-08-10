<?php
namespace App;
use Jenssegers\Mongodb\Eloquent\Model;

class Users extends Model
{
    protected $collection = 'users';
    protected $primaryKey = '_id';

}	
?>