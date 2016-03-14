@extends('layouts.default')
@section('title', 'Things To Do in Brisbane')
@section('description', 'Find Great Activities and Events from around Brisbane')
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <h4>Most Popular Tags</h4>
                <ul class="list-unstyled">
                    @foreach($categories as $category)
                    <li>
                        <a class="text-capitalize" href="{{ url("{$city->slug}/{$category->slug}") }}">
                            {{ $category->name }} <span class="badge">{{ $category->activities->count() }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-sm-8">
                @foreach($categories as $category)
                    <h2>{{ $category->name }}</h2>

                    @include('includes.activities', ["activities" => $category->activities->count() >= 4 ? $category->activities->take(4) : $category->activities])
                @endforeach
            </div>
        </div>

    </div>
@stop