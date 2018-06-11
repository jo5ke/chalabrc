<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    protected $fillable = [
        'name','number_of_rounds'
    ];

    public function seasons()
    {
        return $this->hasMany('App\Season');
    }

    public function rounds() {
        return $this->hasMany('App\Round');
    }

    public function users()
    {
        return $this->belongsToMany('App\User','user_league')->withPivot('money','points','transfers','joined_privates')->withTimestamps();
    }

    public function usersPerLeague($id)
    {
        return $this->hasMany('App\User','user_league')->wherePivot('league_id',$id)->get();
    }

    public function clubs()
    {
        return $this->hasMany('App\Club');
    }

    public function articles()
    {
        return $this->hasMany('App\Article');
    }

    public function transfers()
    {
        return $this->hasMany('App\Transfer');
    }

    public function players()
    {
        return $this->hasMany('App\Player');
    }

    public function oneLeague($id)
    {
        return $this->belongsToMany('App\User','user_league')->wherePivot('league_id',$id)->withPivot('money','points','transfers','privates','joined_privates')->withTimestamps();
    }

    public function privateleagues()
    {
        return $this->hasMany('App\PrivateLeague');
    }

    public function tips()
    {
        return $this->hasMany('App\Tip');
    }

}
