<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'body','title','league_id'
    ];
    
    public function league()
    {
        return $this->belongsTo('App\League');
    }
}
