@extends('layouts.default')
@section('title', ucwords($category->name) . " Things to do in {$city->name}")
@section('description', 'Find ' . $category->name . ' in Brisbane')
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
    }, {
        "@type": "ListItem",
        "position": 2,
        "item": {
            "@id": "{{ url("{$city->slug}/{$category->slug}") }}",
            "name": "{{ $category->name }}"
        }
    }]
}
@stop

@section('content')
    <div class="container">
        <h1 class="text-capitalize">{{ $category->name }} in {{ $city->name }}</h1>
        @include('includes.activities', ["activities" => $category->activities])
    </div>
@stop