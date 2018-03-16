<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    public function seasons()
    {
        return $this->hasMany('App\Season');
    }

    public function users()
    {
        return $this->belongsToMany('App\User','user_league')->withTimestamps();
    }

    public function clubs()
    {
        return $this->hasMany('App\Club');
    }

    public function articles()
    {
        return $this->hasMany('App\Article');
    }

    public function transfers()
    {
        return $this->hasMany('App\Transfer');
    }

}
