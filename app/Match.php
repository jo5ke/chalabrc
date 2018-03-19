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

    public function league() {
        return $this->belongsTo('App\League');
    }

    public function clubs()
    {
        return $this->hasMany('App\Club');
    }

    // public function clubs()
    // {
    //     return $this->belongsToMany('App\Club' , 'matches_clubs','match_id','club_id')->withPivot('club1_name','club2_name')->withTimestamps();
    // }
}
