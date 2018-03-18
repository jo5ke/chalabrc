<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    protected $fillable = [
        'season_id','league_id','round_no'
    ];
    public function season()
    {
        return $this->belongsTo('App\Season');
    }

    public function matches()
    {
        return $this->hasMany('App\Match');
    }

    public function players()
    {
        return $this->belongsToMany('App\Players','player_round')->withTimestamps();
    }
}
