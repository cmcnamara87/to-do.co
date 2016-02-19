@foreach (array_chunk($activities->all(), 4) as $activitiesRow)
    <div class="row">
        @foreach ($activitiesRow as $activity)
            <div class="col-sm-3">
                <div class="panel panel-default">
                    <div>
                        <img src="{{ asset($activity->image_url) }}" alt="" style="width:100%;"/>
                    </div>
                    <div class="panel-body">
                        <h3>{{ $activity->title }}</h3>
                        <div>
                            {!! $activity->description !!}
                        </div>
                    </div>
                    <ul class="list-group">
                        @foreach($activity->timetables as $timetable)
                            <li class="list-group-item">
                                {{ $timetable->start_time->toDayDateTimeString() }} -
                                {{ $timetable->end_time->toDayDateTimeString() }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
@endforeach