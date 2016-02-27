<nav class="navbar navbar-default navbar-fixed-top">
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

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li role="presentation" class="{{ $when == 'today' ? 'active' : '' }}"><a href="{{ url("brisbane/today/{$sort}") }}">Today</a></li>
                <li role="presentation" class="{{ $when == 'tomorrow' ? 'active' : '' }}"><a href="{{ url("brisbane/tomorrow/{$sort}") }}">Tomorrow</a></li>
                <li role="presentation" class="{{ $when == 'this-weekend' ? 'active' : '' }}"><a href="{{ url("brisbane/this-weekend/{$sort}") }}">This Weekend</a></li>
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