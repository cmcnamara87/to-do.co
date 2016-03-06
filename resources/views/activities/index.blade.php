@extends('layouts.default')
@section('title', 'Things To Do in Brisbane | To-Do.Co')
@section('description', 'Find Great Activities and Events from around Brisbane')
@section('content')

    <div class="jumbotron">
        <div class="container">
            <h1>Things To Do in Brisbane</h1>
            <p>Find Great Activities and Events from around Brisbane</p>
        </div>
    </div>
    <div class="container">

        <div class="row">
            <div class="col-sm-12">
                <h2>Featured</h2>
                <a href="{{ url("brisbane/today/cool") }}">See More</a>
                <div style="margin-top:10px;">@include('includes.activities', ["activities" => $featured])</div>

                <h2>Soon</h2>
                <a href="{{ url("brisbane/today/soon") }}">See More</a>
                <div style="margin-top:10px;">@include('includes.activities', ["activities" => $soon])</div>

                <h2>Food</h2>
                <a href="{{ url("categories/food-and-drink") }}">See More</a>
                <div style="margin-top:10px;">@include('includes.activities', ["activities" => $food])</div>

                <h2>Movies</h2>
                <a href="{{ url("categories/movies") }}">See More</a>
                <div style="margin-top:10px;">@include('includes.activities', ["activities" => $movies])</div>

                <h2>Free</h2>
                <a href="{{ url("categories/free") }}">See More</a>
                <div style="margin-top:10px;">@include('includes.activities', ["activities" => $free])</div>

                <h2>Cheap</h2>
                <a href="{{ url("categories/cheap") }}">See More</a>
                <div style="margin-top:10px;">@include('includes.activities', ["activities" => $cheap])</div>
            </div>
        </div>
    </div>
@stop