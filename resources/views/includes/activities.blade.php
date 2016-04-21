@foreach (array_chunk($activities->all(), 4) as $activitiesRow)
    <div class="list-group" style="margin-bottom: 30px;">
        @foreach ($activitiesRow as $activity)
            <div class="list-group-item">
                {{--<div class="panel panel-default">--}}
                <div style="position: relative">
                    <div class="media">
                        <div class="pull-left">
                            <img src="{{ asset($activity->image_url) }}" alt="{{ $activity->title }}" style="width:100px;"/>
                        </div>
                        <div class="media-body">
                            <h5 style="margin: 0;">
                                <a href="{{ url("activities/{$activity->slug}") }}">{{ $activity->title }}</a>
                            </h5>
                            <p class="text-muted">
                                @if($activity->price == 0)
                                    Free
                                @elseif($activity->price > 0)
                                    ${{ $activity->price }}
                                    {{--@if($activity->price != $activity->value)--}}
                                    {{--was ${{ $activity->value }}--}}
                                    {{--@endif--}}
                                @endif
                            </p>
                            {{--<ul class="list-unstyled text-muted">--}}
                                {{--@foreach($activity->timetables->take(1) as $firstTimetable)--}}
                                    {{--@if($firstTimetable->start_time->day == $firstTimetable->end_time->day)--}}
                                        {{--<em>{{ $firstTimetable->start_time->format('l j F Y') }}</em>--}}
                                        {{--{{ $firstTimetable->start_time->format('h:i A') }} ---}}
                                        {{--{{ $firstTimetable->end_time->format('h:i A') }}--}}
                                    {{--@else--}}
                                        {{--<em>{{ $firstTimetable->start_time->format('l j F Y') }}</em>--}}
                                        {{--{{ $firstTimetable->start_time->format('h:i A') }} ---}}
                                        {{--<em>{{ $firstTimetable->end_time->format('l j F Y') }}</em>--}}
                                        {{--{{ $firstTimetable->end_time->format('h:i A') }}--}}
                                    {{--@endif--}}
                                {{--@endforeach--}}

                                {{--                            Next On: {{ $firstTimetable->start_time->toDateTimeString() }}--}}
                                {{--                            - {{ $firstTimetable->end_time->toDateTimeString() }}--}}
                            {{--</ul>--}}

                            <ul class="list-inline">
                                @foreach($activity->categories->take(2) as $category)
                                    <li><span class="label label-info">{{ $category->name }}</span></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                {{--</div>--}}
                {{--</div>--}}
            </div>
        @endforeach
        <li class="list-group-item">
            <a href="">View more things on today</a>
        </li>
    </div>
@endforeach

