<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'position', 'wont_play'
    ];

    public function club()
    {
        return $this->belongsTo('App\Club');
    }

    public function squads()
    {
        return $this->belongsToMany('App\Squad','squad_player')->withTimestamps();
    }

    public function rounds()
    {
        return $this->belongsToMany('App\Round','player_round')->withTimestamps();
    }
}
