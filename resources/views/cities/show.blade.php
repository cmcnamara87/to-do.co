@extends('layouts.default')
@section('title', 'Things To Do in Brisbane')
@section('description', 'Find Great Activities and Events from around Brisbane')
@section('breadcrumb')
{
    "@context": "http://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "item": {
            "@id": "{{ url("{$city->slug}") }}",
            "name": "{{ $city->name }}"
        }
    }]
}
@stop
@section('content')

    {{--<div class="jumbotron">--}}
        {{--<div class="container">--}}
            {{--<h1>Things To Do in {{ $city->name }}</h1>--}}

            {{--<p>Find Great Activities and Events in {{ $city->name }}</p>--}}
        {{--</div>--}}
    {{--</div>--}}



    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h4>Most Popular Tags</h4>
                    </div>

                    <div class="list-group">
                        @foreach($categories as $category)
                            <a class="list-group-item" class="text-capitalize" href="{{ url("{$city->slug}/{$category->slug}") }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-sm-9">
                <h1 class="text-center" style="font-weight: bold;font-size:60px;">Things To Do in {{ $city->name }}</h1>
                @foreach($categories as $category)
                    <div style="margin-bottom: 50px;">
                        <h2 class="text-capitalize text-center" style="font-size:40px;font-weight:bold;">
                            <a style="color: inherit;"
                               href="{{ url("{$city->slug}/{$category->slug}") }}">{{ $category->name }}</a></h2>
                        <p style="margin-bottom: 20px;" class="text-center text-muted">Check out all the {{ $category->name }}
                            activities in {{ $city->name }}</p>
                        @include('includes.activities', ["activities" => $category->activities->count() >= 4 ? $category->activities->take(4) : $category->activities])
                    </div>

                @endforeach
            </div>
        </div>

    </div>
@stop