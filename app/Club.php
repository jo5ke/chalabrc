<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    protected $fillable = [
        'name','league_id'
    ];

    // public function matches()
    // {
    //     return $this->belongsTo('App\Match');
    // }

    public function players()
    {
        return $this->hasMany('App\Player');
    }

    public function matches()
    {
        return $this->belongsToMany('App\Match' , 'matches_clubs', 'club_id','match_id')->withPivot('club1_name','club2_name')->withTimestamps();
    }

    public function league()
    {
        return $this->belongsTo('App\League');
    }
}
