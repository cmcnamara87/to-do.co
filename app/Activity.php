<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['title', 'description', 'image_url'];

    /**
     * Get the timetables for the blog post.
     */
    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
}
