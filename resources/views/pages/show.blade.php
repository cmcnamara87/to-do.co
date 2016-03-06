@extends('layouts.default')
@section('title', $title)
@section('description', $description)
@section('content')

    <div class="jumbotron">
        <div class="container">
            <h1>{{ $title }}</h1>
            <p>{{ $description }}</p>
        </div>
    </div>

    <div class="container">
        <div style="margin-top:10px;">@include('includes.activities', ["activities" => $activities])</div>
    </div>
@stop