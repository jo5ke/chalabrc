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
}
