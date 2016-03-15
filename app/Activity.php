<?php

namespace App;

use Carbon\Carbon;
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


    protected $fillable = [
        'title',
        'description',
        'image_url',
        'weblink',
        'price',
        'value',
        'city_id'
    ];

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
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Scope a query to only include users of a given type.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhen($query, Carbon $start, Carbon $end)
    {
        return $query->whereHas('timetables', function ($query) use ($start, $end) {
            $query->where('end_time', '>=', $start); // not over yet
            $query->where('start_time', '<=', $end); // but it starts before today is ova
        })->with(['categories', 'timetables' => function ($q) use ($start) {
            $q->where('end_time', '>=', $start);
        }]);
    }

}
