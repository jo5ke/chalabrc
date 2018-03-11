<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    public function matches()
    {
        return $this->belongsTo('App\Match');
    }

    public function player()
    {
        return $this->hasMany('App\Player');
    }
}
