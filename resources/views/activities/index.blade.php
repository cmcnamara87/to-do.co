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

    <div class="container" >
        <div class="row">
            @foreach (array_chunk($activities->all(), 3) as $activitiesRow)
                <div class="row">
                    @foreach ($activitiesRow as $activity)
                        <div class="col-sm-4">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <h3>{{ $activity->title }}</h3>
                                    <div>
                                        {!! $activity->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@stop