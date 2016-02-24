<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Feature;
use App\Timetable;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActivitiesController extends Controller
{
    public function index() {
        $day = Carbon::now();
        $activities = Activity::whereHas('timetables', function($query) use ($day) {
            $query->where('end_time', '>=', $day);
        })->with(['timetables' => function ($q) {
            $q->where('end_time', '>=', Carbon::now());
        }])->get();

        return view('activities.index', compact('activities'));
    }

    public function show($activity) {
        return view('activities.show', compact('activity'));
    }
}
