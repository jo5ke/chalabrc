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
        return $this->belongsToMany('App\Round','round_player')
        ->withPivot('start','sub','assist','miss','k_score','d_score','m_score','a_score','kd_clean','m_clean','k_save','kd_3strike','yellow','red','own_goal','captain')
        ->withTimestamps();
    }

    public function league()
    {
        return $this->belongsTo('App\League');
    }
}
