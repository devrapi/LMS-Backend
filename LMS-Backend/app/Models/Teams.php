<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teams extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'coach',
        'brgy',
        'division_id'

    ];

    public  function division () {
        return $this->belongsTo(Division::class);
    }


    // public function players()
    // {
    //     return $this->hasMany(Player::class);
    // }

    // public function standing()
    // {
    //     return $this->hasOne(Standing::class);
    // }

    // public function homeSchedules()
    // {
    //     return $this->hasMany(Schedule::class, 'team1_id');
    // }

    // public function awaySchedules()
    // {
    //     return $this->hasMany(Schedule::class, 'team2_id');
    // }
}
