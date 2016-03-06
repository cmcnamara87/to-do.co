@extends('layouts.default')
@section('title', $sort)
@section('description', 'Find ' . $sort . ' things to do in Brisbane')
@section('content')
    <div class="container">
        <h1 class="text-capitalize">{{ $sort }}</h1>
        @include('includes.activities', ["activities" => $activities])
    </div>
@stop