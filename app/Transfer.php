<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    public function squad()
    {
        return $this->belongsTo('App\Squad');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function season()
    {
        return $this->belongsTo('App\Season');
    }
}
