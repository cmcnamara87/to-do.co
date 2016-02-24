@foreach (array_chunk($activities->all(), 4) as $activitiesRow)
    <div class="row">
        @foreach ($activitiesRow as $activity)
            <div class="col-sm-3">
                <div class="panel panel-default">
                    <div>
                        <a href="{{ url("{$activity->slug}") }}">
                            <img src="{{ asset($activity->image_url) }}" alt="{{ $activity->title }}" style="width:100%;"/>
                        </a>
                    </div>
                    <div class="panel-body">
                        <h3><a href="{{ url("{$activity->slug}") }}">{{ $activity->title }}</a></h3>
                        <div>
                            {!! str_limit(strip_tags($activity->description), 150, '...') !!}
                        </div>
                    </div>
                    <ul class="list-group">
                        @foreach($activity->timetables as $timetable)
                            <li class="list-group-item">
                                @if($timetable->start_time->day == $timetable->end_time->day)
                                    <strong>{{ $timetable->start_time->format('l j F Y') }}</strong>
                                    {{ $timetable->start_time->format('h:i A') }} -
                                    {{ $timetable->end_time->format('h:i A') }}
                                @else
                                    <strong>{{ $timetable->start_time->format('l j F Y') }}</strong>
                                    {{ $timetable->start_time->format('h:i A') }} -
                                    <strong>{{ $timetable->end_time->format('l j F Y') }}</strong>
                                    {{ $timetable->end_time->format('h:i A') }}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
@endforeach