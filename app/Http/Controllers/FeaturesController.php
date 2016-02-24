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
        $features = Feature::orderBy('date', 'desc')->with(['activities.timetables' => function ($q) {
            $q->where('end_time', '>=', Carbon::now());
        }])->get();
        return view('features.index', compact('features'));
    }
}
