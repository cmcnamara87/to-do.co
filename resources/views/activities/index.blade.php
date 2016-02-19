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
        <h2>Today</h2>
        @include('includes.activities', ["activities" => $todaysActivites])
    </div>

    <div class="container">
        <h2>This Weekend</h2>
        @include('includes.activities', ["activities" => $thisWeekendsActivites])
    </div>
@stop