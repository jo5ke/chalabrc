<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id' , 'password', 'remember_token',
    ];

    public function squads()
    {
        return $this->hasMany('App\Squad');
    }

    public function leagues()
    {
        return $this->belongsToMany('App\League','user_league')->withPivot('money','points')->withTimestamps();
    }

    public function transfers()
    {
        return $this->hasMany('App\Transfer');
    }


}
