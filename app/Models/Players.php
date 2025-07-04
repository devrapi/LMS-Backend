<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Players extends Model
{
    protected $fillable = [
        'name', 'team_id', 'jersey_number', 'position', 'height', 'age' , 'profile'
    ];

    public function team()
    {
        return $this->belongsTo(Teams::class);
    }

    // public function stats()
    // {
    //     return $this->hasMany(Stat::class);
    // }
}
