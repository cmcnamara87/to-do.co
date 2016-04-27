<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Category;
use App\City;
use App\Feature;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CitiesController extends Controller
{
    public function index()
    {
        $cities = City::all();
        return view('cities.index', compact('cities'));
    }

    public function show(City $city)
    {

        $features = Feature::all();
        dd($features);

        $start = Carbon::now();


        $categories = Category::whereHas('activities', function ($q) use ($city) {
            $q->where('city_id', $city->id);
        })
            ->with(['activities' => function ($q) use ($city) {
                $q->where('city_id', $city->id);
            }, 'activities.timetables' => function($query) use ($start) {
                $query->where('end_time', '>=', $start); // not over yet
            }, 'activities.categories'])
            ->get()
            ->sortByDesc(function ($category) {
                return $category->activities->count();
            });


//        $activitiesByCategory = array_reduce($categories->all(), function ($carry, $category) use ($start) {
//            $activities = Activity::whereHas('timetables', function ($query) use ($start) {
//                $query->where('end_time', '>=', $start); // not over yet
//            })->whereHas('categories', function ($query) use ($category) {
//                $query->where('category_id', $category->id);
//            })->with(['categories', 'timetables' => function ($q) use ($start) {
//                $q->where('end_time', '>=', $start);
//            }])->orderBy(DB::raw('RAND()'))->take(10)->get();
//
//            $carry[] = [
//                "category" => $category,
//                "activities" => $activities
//            ];
//            return $carry;
//        }, []);


        return view('cities.show', compact('city', 'categories'));
    }
}
