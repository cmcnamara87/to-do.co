<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Decision extends Model
{
    //
    protected $fillable = ['activity_id', 'decision', 'user_id']; //
}
