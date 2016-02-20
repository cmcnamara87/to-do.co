@extends('layouts.default')
@section('title', $activity->title)
@section('description', $activity->description)
@section('content')
    <div class="container">
        <h2>{{ $activity->title }}</h2>
        <div class="row">
            <div class="col-sm-3">
                <img src="{{ $activity->image_url }}" alt="{{ $activity->title }}" class="img-responsive"/>
            </div>
            <div class="col-sm-9">
                {{ $activity->description }}
                <a href="{{ $activity->weblink }}">{{ $activity->weblink }}</a>
            </div>
        </div>
    </div>
@stop