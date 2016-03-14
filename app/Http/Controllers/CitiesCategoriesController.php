<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Category;
use App\City;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CitiesCategoriesController extends Controller
{
    public function show(City $city, Category $category) {
        $start = Carbon::now();

        $activities = Activity::whereHas('timetables', function ($query) use ($start) {
            $query->where('end_time', '>=', $start); // not over yet
        })->whereHas('categories', function($query) use ($category) {
            $query->where('name', $category->name);
        })->with(['categories', 'timetables' => function ($q) use ($start) {
            $q->where('end_time', '>=', $start);
        }])->get();

        return view('categories.show', compact('city', 'category', 'activities'));
    }
}
