<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $fillable = ['*'];
    public function round()
    {
        return $this->belongsTo('App\Round');
    }

    // public function clubs()
    // {
    //     return $this->hasMany('App\Club');
    // }

    public function clubs()
    {
        return $this->belongsToMany('App\Club' , 'match_club')->withTimestamps();
    }
}
