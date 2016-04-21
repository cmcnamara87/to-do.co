<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function show(Category $category) {
        return view('categories.show', compact('category'));
    }
}
