@foreach (array_chunk($activities->all(), 4) as $activitiesRow)
    <div class="row" style="margin-bottom: 30px;">
        @foreach ($activitiesRow as $activity)
            <div class="col-sm-3">
                {{--<div class="panel panel-default">--}}
                    <div>
                        @if(isset($activity->score))
                            <strong>{{ $activity->score }}</strong>
                        @endif
                        <a href="{{ url("activities/{$activity->slug}") }}">
                            <img src="{{ asset($activity->image_url) }}" alt="{{ $activity->title }}" style="width:100%;"/>
                        </a>
                    </div>
                    {{--<div class="panel-body">--}}
                        <h5>
                            <a href="{{ url("activities/{$activity->slug}") }}">{{ $activity->title }}</a>
                        </h5>
                        <ul class="list-unstyled">
                            @foreach($activity->timetables as $timetable)
                                <li class="text-muted">{{ $timetable->start_time->toDateTimeString() }}
                                    - {{ $timetable->end_time->toDateTimeString() }}</li>
                            @endforeach
                        </ul>
                        <p class="text-muted">
                            @if($activity->price == 0)
                                Free
                            @elseif($activity->price > 0)
                                ${{ $activity->price }}
                                @if($activity->price != $activity->value)
                                    was ${{ $activity->value }}
                                @endif
                            @endif
                        </p>
                        <ul class="list-inline">
                            @foreach($activity->categories as $category)
                                <li><span class="label label-default">{{ $category->name }}</span></li>
                            @endforeach
                        </ul>

                    {{--</div>--}}
                {{--</div>--}}
            </div>
        @endforeach
    </div>
@endforeach

