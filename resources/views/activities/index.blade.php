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

        <ul class="nav nav-pills">
            <li role="presentation" class="{{ $when == 'today' ? 'active' : '' }}"><a href="{{ url("brisbane/today/{$sort}") }}">Today</a></li>
            <li role="presentation" class="{{ $when == 'tomorrow' ? 'active' : '' }}"><a href="{{ url("brisbane/tomorrow/{$sort}") }}">Tomorrow</a></li>
            <li role="presentation" class="{{ $when == 'this-weekend' ? 'active' : '' }}"><a href="{{ url("brisbane/this-weekend/{$sort}") }}">This Weekend</a></li>
        </ul>




        <h3>Sort By</h3>
        <div class="btn-group" role="group" aria-label="...">
            <a class="btn btn-default {{ $sort == 'cool' ? 'active' : '' }}" href="{{ url("brisbane/{$when}/cool") }}">Cool</a>
            <a class="btn btn-default {{ $sort == 'soon' ? 'active' : '' }}" href="{{ url("brisbane/{$when}/soon") }}">Soon</a>
        </div>

        <div style="margin-top:10px;">@include('includes.activities', ["activities" => $activities])</div>
    </div>
@stop