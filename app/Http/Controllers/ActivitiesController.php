<?php

namespace App\Http\Controllers;

use App\Activity;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ActivitiesController extends Controller
{
    public function index()
    {
        $activities = Activity::all();
        // load the view and pass the nerds
        return view('activities.index', compact('activities'));
    }
}
