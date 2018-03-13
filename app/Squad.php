<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Squad extends Model
{
    //
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function players()
    {
        return $this->belongsToMany('App\Players','squad_player')->withTimestamps();
    }
}
