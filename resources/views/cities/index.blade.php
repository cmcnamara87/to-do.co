@extends('layouts.default')
@section('title', 'Things To Do in Brisbane | To-Do.Co')
@section('description', 'Find Great Activities and Events from around Brisbane')
@section('content')

    <div class="container">
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