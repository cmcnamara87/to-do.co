@extends('layouts.default')
@section('title', 'Things To Do in Brisbane')
@section('description', 'Find Great Activities and Events from around Brisbane')
@section('content')

    <div class="container">
        <h2>Categories</h2>
        <div class="row">

                @foreach($categories as $category)
                <div class="col-sm-2">
                    <div class="panel panel-default" style="color: white;height: 150px;background:
    linear-gradient(
      to bottom,
                            RGBA(0, 251, 176, 1),
                            RGBA(0, 251, 176, 1)
    ),
    url({{ $category->activities->get(0)['image_url'] }}); display: flex;
                            justify-content:center;
                            align-content:center;
                            flex-direction:column; text-transform: capitalize">

                        <div class="text-center">
                            {{ $category->name }}
                        </div>

                    </div>
                </div>
                @endforeach

        </div>


        <ul>

        </ul>
    </div>
@stop