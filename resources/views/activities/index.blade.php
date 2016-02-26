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
        <h2>Activities</h2>
        <ul>
            <li><a href="{{ url("brisbane/today/cool") }}">Coolest</a></li>
            <li><a href="{{ url("brisbane/today/soon") }}">Soonest</a></li>
        </ul>
        @include('includes.activities', ["activities" => $activities])
    </div>
@stop