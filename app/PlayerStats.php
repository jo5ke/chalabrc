<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayerStats extends Model
{
    public function match()
    {
        return $this->belongsTo('App\Match');
    }

}
