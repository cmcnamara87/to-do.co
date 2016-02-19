<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Timetable;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ActivitiesController extends Controller
{
    public function index()
    {
        $sunday = Carbon::parse('Saturday');
        if (Carbon::today()->isSunday()) {
            $saturday = Carbon::parse('Last Saturday');
        } else {
            $saturday = Carbon::parse('Saturday');
        }

        $activities = Activity::all();

        $timetableIds = Timetable::where('start_time', '>=', Carbon::now())->where('start_time', '<', Carbon::today()->endOfDay())->lists('activity_id');
        $todaysActivites = Activity::whereIn('id', $timetableIds)->with(array('timetables' => function ($q) {
            $q->where('start_time', '>=', Carbon::now());
        }))->get();

        $timetableIds = Timetable::where('start_time', '>=', $saturday)->where('start_time', '<', $sunday->endOfDay())->lists('activity_id');
        $thisWeekendsActivites = Activity::whereIn('id', $timetableIds)->with(array('timetables' => function ($q) {
            $q->where('start_time', '>=', Carbon::now());
        }))->get();

        // load the view and pass the nerds
        return view('activities.index', compact('activities', 'todaysActivites', 'thisWeekendsActivites'));
    }
}
