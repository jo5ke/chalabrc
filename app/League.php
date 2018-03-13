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
}
