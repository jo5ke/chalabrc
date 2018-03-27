<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Squad extends Model
{
    //
    protected $hidden = [
        'user_id' , 
    ];
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function players()
    {
        return $this->belongsToMany('App\Player','squad_player')->withTimestamps();
    }

    public function updatePlayers($user_id)
    {
        return $this->belongsToMany('App\Player','squad_player')->wherePivot('user_id',$user_id )->withTimestamps();
    }

    public function transfers()
    {
        return $this->hasMany('App\Transfer');
    }

    public function rounds()
    {
        return $this->belongsToMany('App\Round','squad_round')->withPoints('points')->withTimestamps();
    }
}
