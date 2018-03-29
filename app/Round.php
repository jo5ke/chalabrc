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

    public function league() {
        return $this->belongsToMany('App\League');
    }

    public function players()
    {
        return $this->belongsToMany('App\Player','round_player')
        ->withPivot('start','sub','assist','miss','score','clean','k_save','kd_3strike','yellow','red','own_goal','captain','total')
        ->withTimestamps();
    }

    public function squad()
    {
        return $this->belongsToMany('App\Squad','squad_round')->withPivot('points')->withTimestamps();
    }
}
