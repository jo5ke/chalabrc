<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    public function season()
    {
        return $this->belongsTo('App\Season');
    }

    public function matches()
    {
        return $this->hasMany('App\Match');
    }
}
