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
        'name', 'email', 'password','first_name','last_name','username','birthdate','city','country'
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
        return $this->belongsToMany('App\League','user_league')->withPivot('money','points','transfers')->withTimestamps();
    }

    public function transfers()
    {
        return $this->hasMany('App\Transfer');
    }

    public function oneLeague($id)
    {
        return $this->belongsToMany('App\League','user_league')->wherePivot('league_id',$id)->withPivot('money','points','transfers','privates')->withTimestamps()->get();
    }
    
    public function roles()
    {
        return $this->belongsToMany('App\Role')->withPivot('secret');
    }

    public function privateleagues()
    {
        return $this->hasMany('App\PrivateLeagues','owner_id','id');
    }


}
