@extends('layouts.default')
@section('title', $activity->title)
@section('description', $activity->description)
@section('content')
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-4">
                        <img src="{{ $activity->image_url }}" alt="{{ $activity->title }}" class="img-responsive"/>
                    </div>
                    <div class="col-sm-8">
                        <h1>{{ $activity->title }}</h1>
                        {{ $activity->description }}
                        <a href="{{ $activity->weblink }}">{{ $activity->weblink }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop