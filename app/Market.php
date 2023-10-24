<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Market extends Eloquent
{
    protected $connection = "mongodb";
    protected $collection = 'market';

    protected $fillable = [
        'name',
        'gamename',
        'market',
        'mon_start_time',
        'mon_close_time',
        'tue_start_time',
        'tue_close_time',
        'wed_start_time',
        'wed_close_time',
        'thu_start_time',
        'thu_close_time',
        'fri_start_time',
        'fri_close_time',
        'sat_start_time',
        'sat_close_time',
        'sun_start_time',
        'sun_close_time',
        'bucket',
        'bucket3',
        'bucket5',
        'status',
    ];
}
