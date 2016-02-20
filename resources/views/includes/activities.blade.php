@foreach (array_chunk($activities->all(), 4) as $activitiesRow)
    <div class="row">
        @foreach ($activitiesRow as $activity)
            <div class="col-sm-3">
                <div class="panel panel-default">
                    <div>
                        <img src="{{ asset($activity->image_url) }}" alt="" style="width:100%;"/>
                    </div>
                    <div class="panel-body">
                        <h3><a href="{{ $activity->weblink }}">{{ $activity->title }}</a></h3>
                        <div>
                            {!! $activity->description !!}
                        </div>
                    </div>
                    @if($activity->timetables->count())
                    <ul class="list-group">
                        <?php $firstTimetable = $activity->timetables[0]; ?>
                        <li class="list-group-item">
                            {{ $firstTimetable->start_time->toDayDateTimeString() }} -
                            {{ $firstTimetable->end_time->toDayDateTimeString() }}
                        </li>
                        @if($activity->timetables->count() > 1)
                        <li class="list-group-item">+ {{ $activity->timetables->count() - 1 }} more times</li>
                        @endif
                    </ul>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endforeach