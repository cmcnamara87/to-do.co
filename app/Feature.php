<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = ["date"];
    protected $dates = ['created_at', 'updated_at', 'date'];

    public function activities()
    {
        return $this->belongsToMany(Activity::class);
    }

}
