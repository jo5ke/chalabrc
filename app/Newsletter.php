<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $fillable = ['*'];
    
    public function league()
    {
        return $this->belongsTo('App\League');
    }
}
