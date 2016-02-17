<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    protected $fillable = ['activity_id', 'start_time', 'end_time'];
    protected $dates = ['created_at', 'updated_at', 'start_time', 'end_time'];
}
