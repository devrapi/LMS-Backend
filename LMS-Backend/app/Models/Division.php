<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $fillable = ['name', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    public function teams()
    {
        // return $this->hasMany(Team::class);
    }

    public function schedules()
    {
        // return $this->hasMany(Schedule::class);
    }
}
