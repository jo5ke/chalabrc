<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tip extends Model
{
    protected $fillable = [
        'subject', 'body'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function league()
    {
        return $this->belongsTo('App\League');
    }
}
