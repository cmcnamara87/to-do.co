@extends('layouts.default')
@section('title', 'Things To Do in Brisbane')
@section('description', 'Find Great Activities and Events from around Brisbane')
@section('content')

    <div class="jumbotron">
        <div class="container">
            <h1>Things To Do in Brisbane</h1>
            <p>Find Great Activities and Events from around Brisbane</p>
        </div>
    </div>
    <div class="container">
        <h2>Categories</h2>
        <ul>
            @foreach($categories as $category)
            <li class="text-capitalize">
                <a href="{{ url("categories/{$category->slug}") }}">{{ $category->name }}</a>
            </li>
            @endforeach
        </ul>
    </div>
@stop