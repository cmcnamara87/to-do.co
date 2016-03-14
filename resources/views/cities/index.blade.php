@extends('layouts.default')
@section('title', 'Things To Do in Your City | To-Do.Co')
@section('description', 'Find Great Activities and Events around your city.')
@section('content')

    <div class="jumbotron">
        <div class="container">
            <h1>Things To Do</h1>
            <p>Find Great Activities and Events from around Your City</p>
        </div>
    </div>

    <div class="container">
        <div class="text-center">
            <h2>Let's Get Started!</h2>
            <p class="text-muted">Select your city from the list below</p>
        </div>

        <ul>
            @foreach($cities as $city)
            <li>
                <a href="{{ url("{$city->slug}") }}" title="{{ $city->name }}">
                    {{ $city->name }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
@stop