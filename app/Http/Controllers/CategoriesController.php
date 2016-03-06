<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    public function index()
    {
        $start = Carbon::now();
        $end = Carbon::now()->endOfDay();
        $display = 8;

        $soon = Activity::when($start, Carbon::tomorrow()->endOfDay())->get()->sortBy(function ($activity) {
            $nextTimetable = $activity->timetables[0];
            return Carbon::now()->diffInMinutes($nextTimetable->start_time);
        })->take($display);

        $categoryNames = [
            "Sir Thomas Brisbane Planetarium",
            "Festivals",
            "LIVE",
            "Music and concert",
            "Brisbane Powerhouse",
            "Riverstage",
            "Brisbane Markets"
        ];

        $featured = Activity::when($start, $end)->whereHas('categories', function ($query) use ($categoryNames) {
            $query->whereIn('name', $categoryNames);
        })->take($display)->get();


        $food = Activity::when($start, $end)->whereHas('categories', function ($query) use ($categoryNames) {
            $query->where('name', 'food and drink');
        })->take($display)->get();

        $movies = Activity::when($start, $end)->whereHas('categories', function ($query) use ($categoryNames) {
            $query->where('name', '=', 'movies');
        })->take($display)->get();

        $free = Activity::when($start, $end)->where('price', 0)->take($display)->get();
        $cheap = Activity::when($start, $end)->where('price', '>', 0)->where('price', '<=', 20)->take($display)->get();

        return view('activities.index', compact('soon', 'featured', 'food', 'movies', 'free', 'cheap'));
    }

    public function show(Category $category) {
        return view('categories.show', compact('category'));
    }
}
