@extends('layouts.default')
@section('title', 'Things To Do in Brisbane | To-Do.Co')
@section('description', 'Find Great Activities and Events from around Brisbane')
@section('content')

    <div class="jumbotron">
        <div class="container">
            <h1>10 Cools Things To Do in Brisbane Every Day</h1>
            <p>Find Great Activities and Events from around Brisbane</p>
        </div>
    </div>
    <div class="container">
        <h2>Featured</h2>
        @foreach($features as $feature)
            <h3>{{ $feature->date->format('l') }}</h3>
            @include('includes.feature', ["feature" => $feature])
        @endforeach
    </div>
@stop