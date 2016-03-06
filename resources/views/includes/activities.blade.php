@foreach (array_chunk($activities->all(), 4) as $activitiesRow)
    <div class="row">
        @foreach ($activitiesRow as $activity)
            <div class="col-sm-3">
                <div class="panel panel-default">
                    <div>
                        @if(isset($activity->score))
                            <strong>{{ $activity->score }}</strong>
                        @endif
                        <a href="{{ url("{$activity->slug}") }}">
                            <img src="{{ asset($activity->image_url) }}" alt="{{ $activity->title }}" style="width:100%;"/>
                        </a>
                    </div>
                    <div class="panel-body">
                        <h3><a href="{{ url("{$activity->slug}") }}">{{ $activity->title }}</a></h3>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endforeach