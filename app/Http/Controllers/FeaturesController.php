<?php

namespace App\Http\Controllers;

use App\Feature;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FeaturesController extends Controller
{
    public function index()
    {
        $features = Feature::orderBy('date', 'desc')->get();
        return view('features.index', compact('features'));
    }
}
