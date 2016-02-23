
<div class="row">
    <div class="col-sm-8 ">
        <ul class="list-group">
            @foreach($feature->activities as $activity)
                <li class="list-group-item">
                    <div class="media">
                        <div class="pull-left">
                            <img src="{{ asset($activity->image_url) }}" alt="{{ $activity->title }}" style="width:100px;"/>
                        </div>
                        <div class="media-body">
                            <h4><a href="{{ url("{$activity->slug}") }}">{{ $activity->title }}</a></h4>
                            <p>
                                {!! str_limit(strip_tags($activity->description), 150, '...') !!}
                            </p>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>