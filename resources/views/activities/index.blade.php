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
        <div class="btn-group" role="group" aria-label="...">
            <a class="btn btn-default {{ $sort == 'cool' ? 'active' : '' }}" href="{{ url("brisbane/{$when}/cool") }}">Coolest First</a>
            <a class="btn btn-default {{ $sort == 'soon' ? 'active' : '' }}" href="{{ url("brisbane/{$when}/soon") }}">Soonest First</a>
        </div>

        <div class="row">
            <div class="col-sm-12">

                <div style="margin-top:10px;">@include('includes.activities', ["activities" => $activities])</div>
            </div>
            {{--<div class="col-sm-3">--}}
                {{--<ul class="nav nav-pills nav-stacked">--}}
                    {{--@foreach($categories as $category)--}}
                        {{--<li class="text-capitalize"><a>{{ $category->name }}</a></li>--}}
                    {{--@endforeach--}}
                {{--</ul>--}}
            {{--</div>--}}
        </div>
    </div>
@stop