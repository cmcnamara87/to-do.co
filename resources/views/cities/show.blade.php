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

    <div class="jumbotron">
        <div class="container">
            <h1>Things To Do in {{ $city->name }}</h1>

            <p>Find Great Activities and Events in {{ $city->name }}</p>
        </div>
    </div>



    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <h4>Most Popular Tags</h4>
                <ul class="list-unstyled">
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
                @foreach($categories as $category)
                    <h2 class="text-capitalize"><a
                                href="{{ url("{$city->slug}/{$category->slug}") }}">{{ $category->name }}</a></h2>
                    <p style="margin-bottom: 20px;" class="text-muted">Check out all the {{ $category->name }}
                        activities in {{ $city->name }}</p>
                    @include('includes.activities', ["activities" => $category->activities->count() >= 4 ? $category->activities->take(4) : $category->activities])
                @endforeach
            </div>
        </div>

    </div>
@stop