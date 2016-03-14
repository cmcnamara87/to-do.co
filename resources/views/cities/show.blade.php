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
                @include('includes.activities', ["activities" => $activities])
            </div>
        </div>

    </div>
@stop