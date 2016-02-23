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
        <h2>Featured</h2>
        @foreach($features as $feature)
        <h3>{{ $feature->date->format('l') }}</h3>
        @include('includes.activities', ["activities" => $feature->activities])
        @endforeach
    </div>

    {{--<div class="container">--}}
        {{--<h2>This Weekend</h2>--}}
        {{--@include('includes.activities', ["activities" => $thisWeekendsActivites])--}}
    {{--</div>--}}
@stop