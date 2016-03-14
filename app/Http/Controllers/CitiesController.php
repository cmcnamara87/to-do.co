<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Category;
use App\City;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CitiesController extends Controller
{
    public function index() {
        $cities = City::all();
        return view('cities.index', compact('cities'));
    }
    public function show(City $city) {
        $categories = Category::whereHas('activities', function($q) use ($city) {
            $q->where('city_id', $city->id);
        })
            ->with(['activities' => function($q) use ($city) {
            $q->where('city_id', $city->id);
        }])
            ->get()
            ->sortByDesc(function($category) {
            return $category->activities->count();
        })->values()->all();

        $start = Carbon::now();
        $activities = Activity::whereHas('timetables', function ($query) use ($start) {
            $query->where('end_time', '>=', $start); // not over yet
        })->with(['categories', 'timetables' => function ($q) use ($start) {
            $q->where('end_time', '>=', $start);
        }])->get();
        return view('cities.show', compact('city', 'categories', 'activities'));
    }
}
