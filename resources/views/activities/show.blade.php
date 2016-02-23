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
                        {!! $activity->description !!}
                        <div>
                            <a href="{{ $activity->weblink }}" class="btn btn-primary">Go to Activity</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop