<nav class="navbar navbar-default navbar-fixed-top" style="margin-bottom: 0;">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">
                <img src="{{ asset('images/animat-image-color.gif') }}" alt="" style="    width: 70px;
    margin-left: -20px;
    margin-right: -10px;
    margin-top: -25px;display:inline-block;"/>
                <span style="position:relative;top:-10px;">TodoCo</span></a>
        </div>

        <?php
        if(!isset($sort)) {
            $sort = 'cool';
        }
        ?>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li role="presentation"><a href="http://to-do.co">Home</a></li>
                <li role="presentation"><a href="http://blog.to-do.co">Blog</a></li>
{{--                <li role="presentation" class="{{ $when == 'tomorrow' ? 'active' : '' }}"><a href="{{ url("brisbane/tomorrow/{$sort}") }}">Tomorrow</a></li>--}}
{{--                <li role="presentation" class="{{ $when == 'this-weekend' ? 'active' : '' }}"><a href="{{ url("brisbane/this-weekend/{$sort}") }}">This Weekend</a></li>--}}
            </ul>
            {{--<form class="navbar-form navbar-right" role="search">--}}
                {{--<div class="form-group">--}}
                    {{--<input type="text" class="form-control" placeholder="Search">--}}
                {{--</div>--}}
                {{--<button type="submit" class="btn btn-default">Submit</button>--}}
            {{--</form>--}}
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
{{--<div style="background-color: #F89D85;">--}}
    {{--<div class="container">--}}
        {{--<ul class="nav nav-pills">--}}
            {{--<li role="presentation" class="active"><a href="#">Today</a></li>--}}
            {{--<li role="presentation"><a href="#">Profile</a></li>--}}
            {{--<li role="presentation"><a href="#">Messages</a></li>--}}
        {{--</ul>--}}
        {{--<ul class="nav nav-pills">--}}
            {{--<li>--}}
                {{--<a href="">All</a>--}}
            {{--</li>--}}
            {{--@foreach(\App\Category::all() as $category)--}}
                {{--<li role="presentation"><a href="#">{{ $category->name }}</a></li>--}}
            {{--@endforeach--}}
        {{--</ul>--}}
    {{--</div>--}}

{{--</div>--}}