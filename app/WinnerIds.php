<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class WinnerIds extends Model
{
    protected $collection = 'winnerids';
    protected $primaryKey = '_id';

    public function refer()
    {
        return $this->belongsTo('App\User', 'referralId');
    }
}
