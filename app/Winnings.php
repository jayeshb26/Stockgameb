<?php
namespace App;
use Jenssegers\Mongodb\Eloquent\Model;

class Winnings extends Model
{
    protected $collection = 'winnings';
    protected $primaryKey = '_id';
}	
?>

