<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Timetable;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

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

        $timetableIds = Timetable::where('end_time', '>=', Carbon::now())->where('start_time', '<', Carbon::today()->endOfDay())->lists('activity_id');
        $todaysActivites = Activity::whereIn('id', $timetableIds)->with(array('timetables' => function ($q) {
            $q->where('end_time', '>=', Carbon::now());
        }))->get();
        $todaysActivites = $todaysActivites->sortBy(function ($activity, $key) {
            return array_reduce($activity->timetables->all(), function($carry, $timetable) {
                Log::info($timetable->start_time->toDateTimeString());
                Log::info($timetable->end_time->toDateTimeString());
                Log::info($timetable->start_time->diff($timetable->end_time)->days);
                $diffInDays =  $timetable->start_time->diff($timetable->end_time)->days;
                $carry += max($diffInDays, 1);
                return $carry;
            }, 0);
        });

        $timetableIds = Timetable::where('start_time', '>=', $saturday)->where('start_time', '<', $sunday->endOfDay())->lists('activity_id');
        $thisWeekendsActivites = Activity::whereIn('id', $timetableIds)->with(array('timetables' => function ($q) {
            $q->where('start_time', '>=', Carbon::now());
        }))->get();
        $thisWeekendsActivites = $thisWeekendsActivites->sortBy(function ($activity, $key) {
            return count($activity['timetables']);
        });

        // load the view and pass the nerds
        return view('activities.index', compact('activities', 'todaysActivites', 'thisWeekendsActivites'));
    }
}
