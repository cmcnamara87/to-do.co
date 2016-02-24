@extends('layouts.default')
@section('title', $category->name)
@section('description', 'Find ' . $category->name . ' things to do in Brisbane')
@section('content')
    <div class="container">
        <h1 class="text-capitalize">{{ $category->name }}</h1>
        @include('includes.activities', ["activities" => $category->activities])
    </div>
@stop