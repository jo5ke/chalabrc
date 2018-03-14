<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    // public function matches()
    // {
    //     return $this->belongsTo('App\Match');
    // }

    public function player()
    {
        return $this->hasMany('App\Player');
    }

    public function matches()
    {
        return $this->belongsToMany('App\Match' , 'match_club')->withTimestamps();
    }

    public function league()
    {
        return $this->belongsTo('App\League');
    }
}
