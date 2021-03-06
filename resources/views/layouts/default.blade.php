<!doctype html>
<html>
<head>
    @include('includes.head')
    <meta name="google-site-verification" content="AHYQZDk9GKYjlSEINFaz96FWn5wiZ2rvJJXJ-iQOQPA" />
</head>
<body>
<header>
    @include('includes.header')
</header>
<div>
    <div id="main">
        @yield('content')
    </div>
    {{--<div class="app-banner" style="margin-top:50px;">--}}
        {{--<h3>Get the MoviesOwl App</h3>--}}
        {{--<p>Movies Times, Reviews and Tickets in your Pocket</p>--}}
        {{--<p style="margin-bottom: 0;">--}}
            {{--<a target="_blank" href="https://itunes.apple.com/au/app/moviesowl-find-great-movies/id1032668935?mt=8"><img src="{{ URL::asset('images/appStore_logo.png') }}" alt=""/></a>--}}
            {{--<a target="_blank" href="https://play.google.com/store/apps/details?id=com.moviesowl&hl=en"><img src="{{ URL::asset('images/playstore.png') }}" alt=""/></a>--}}
        {{--</p>--}}
    {{--</div>--}}
    <footer>
        @include('includes.footer')
    </footer>
</div>
<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-56cfa29233423014"></script>

@include('includes.analyticstracking')
</body>
</html>