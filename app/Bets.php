<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Bets extends Model
{
    protected $collection = 'bets';
    protected $primaryKey = '_id';
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
