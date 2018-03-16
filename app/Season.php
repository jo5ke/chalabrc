<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    public function league()
    {
        return $this->belongsTo('App\League');
    }

    public function rounds()
    {
        return $this->hasMany('App\Round');
    }

    public function transfers()
    {
        return $this->hasMany('App\Transfer');
    }
}