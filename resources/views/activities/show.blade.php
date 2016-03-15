@extends('layouts.default')
@section('title', $activity->title)
@section('description', $activity->description)
@section('content')
    <div class="container">
        <ol class="breadcrumb" style="margin-top:-20px;padding: 20px;border-bottom:1px solid #ddd;">
            <li><a href="{{ url("brisbane/{$when}/cool") }}">Brisbane</a></li>
            <li><a class="text-capitalize" href="{{ url("brisbane/{$when}/cool") }}">{{ $when }}</a></li>
            <li class="active">{{ $activity->title }}</li>
        </ol>
    </div>

    <div class="container" style="max-width:600px;">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-9">

                        <h1 style="margin-bottom: 20px;">{{ $activity->title }}</h1>
                        {!! $activity->description !!}
                        <div style="padding: 20px 0;">
                            <a href="{{ $activity->weblink }}" class="btn btn-primary btn-lg">Go to Activity <i class="fa fa-external-link"></i></a>
                        </div>

                        <ul class="list-unstyled">
                            @foreach($activity->timetables as $timetable)
                            <li>
                                @if($timetable->start_time->day == $timetable->end_time->day)
                                    <strong>{{ $timetable->start_time->format('l j F Y') }}</strong>
                                    {{ $timetable->start_time->format('h:i A') }} -
                                    {{ $timetable->end_time->format('h:i A') }}
                                @else
                                    <strong>{{ $timetable->start_time->format('l j F Y') }}</strong>
                                    {{ $timetable->start_time->format('h:i A') }} -
                                    <strong>{{ $timetable->end_time->format('l j F Y') }}</strong>
                                    {{ $timetable->end_time->format('h:i A') }}
                                @endif
                            </li>
                            @endforeach
                        </ul>

                        <ul class="list-unstyled">
                            @foreach($activity->categories as $category)
                                <li class="text-capitalize"><a href="{{ url("{$activity->city->slug}/{$category->slug}") }}">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-sm-3">
                        <img src="{{ $activity->image_url }}" alt="{{ $activity->title }}" class="img-responsive" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop