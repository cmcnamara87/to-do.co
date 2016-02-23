<?php

namespace App;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model implements SluggableInterface
{
    use SluggableTrait;

    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];


    protected $fillable = ['title', 'description', 'image_url', 'weblink'];

    /**
     * Get the timetables for the blog post.
     */
    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class);
    }
}
