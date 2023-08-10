<?php

namespace App;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Jenssegers\Mongodb\Auth\User as Authenticatable;
// use Illuminate\Notifications\Notifiable;
// use Laravel\Passport\HasApiTokens;
// use Illuminate\Auth\Authenticatable as AuthenticableTrait;

use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContracts;
use Laravel\Passport\HasApiTokens;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class User extends Eloquent implements AuthenticatableContracts
{
    use HasApiTokens, Notifiable, Authenticatable;
    protected $connection = "mongodb";
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'userName', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function refer()
    {
        return $this->belongsTo('App\User', 'referralId');
    }
}
