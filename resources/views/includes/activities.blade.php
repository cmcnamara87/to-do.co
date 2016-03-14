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
                        <ul class="list-unstyled text-muted">
                            @foreach($activity->timetables->take(1) as $firstTimetable)
                                @if($firstTimetable->start_time->day == $firstTimetable->end_time->day)
                                    <em>{{ $firstTimetable->start_time->format('l j F Y') }}</em>
                                    {{ $firstTimetable->start_time->format('h:i A') }} -
                                    {{ $firstTimetable->end_time->format('h:i A') }}
                                @else
                                    <em>{{ $firstTimetable->start_time->format('l j F Y') }}</em>
                                    {{ $firstTimetable->start_time->format('h:i A') }} -
                                    <em>{{ $firstTimetable->end_time->format('l j F Y') }}</em>
                                    {{ $firstTimetable->end_time->format('h:i A') }}
                                @endif
                            @endforeach

                                {{--                            Next On: {{ $firstTimetable->start_time->toDateTimeString() }}--}}
{{--                            - {{ $firstTimetable->end_time->toDateTimeString() }}--}}
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

