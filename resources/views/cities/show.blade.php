@extends('layouts.default')
@section('title', 'Things To Do in Brisbane')
@section('description', 'Find Great Activities and Events from around Brisbane')
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <ul>
                    @foreach($categories as $category)
                    <li>
                        <a class="text-capitalize" href="{{ url("{$city->slug}/{$category->slug}") }}">
                            {{ $category->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-sm-8">
                @include('includes.activities', ["activities" => $activities])
            </div>
        </div>

    </div>
@stop