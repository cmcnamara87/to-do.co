<?php

namespace App\Http\Controllers;

use App\Feature;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FeaturesController extends Controller
{
    public function index()
    {
        $features = Feature::orderBy('date', 'desc')->get();
        foreach($features as $feature) {

            $feature->load(['activities.timetables' => function ($q) use ($feature) {
                $q->where('end_time', '>=', Carbon::now());
            }]);
            $feature->activities = $feature->activities->filter(function ($activity) {
                if(!$activity->timetables->count()) {
                    return false;
                }
                return Carbon::now()->lt($activity->timetables->last()->end_time);
            });
        }
        return view('features.index', compact('features'));
    }
}