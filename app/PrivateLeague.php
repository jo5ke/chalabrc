<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrivateLeague extends Model
{
    public function league()
    {
        return $this->belongsTo('App\League');
    }

    public function user()
    {
        return $this->belongsTo('App\User','id','owner_id');
    }
}
