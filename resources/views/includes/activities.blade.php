@foreach (array_chunk($activities->all(), 4) as $activitiesRow)
    <div class="row">
        @foreach ($activitiesRow as $activity)
            <div class="col-sm-3">
                <div class="panel panel-default">
                    <div>
                        <a href="{{ url("activities/{$activity->id}") }}">
                            <img src="{{ asset($activity->image_url) }}" alt="{{ $activity->title }}" style="width:100%;"/>
                        </a>
                    </div>
                    <div class="panel-body">
                        <h3><a href="{{ url("activities/{$activity->id}") }}">{{ $activity->title }}</a></h3>
                        <div>
                            {!! str_limit(strip_tags($activity->description), 150, '...') !!}
                        </div>
                    </div>
                    @if($activity->timetables->count())
                    <ul class="list-group">
                        <?php $firstTimetable = $activity->timetables[0]; ?>
                        @if($activity->timetables->count() == 1 && $firstTimetable->start_time->diff($firstTimetable->end_time)->days > 1)
                            <li class="list-group-item">
                                <strong>Every day</strong> <span class="text-muted">until {{ $firstTimetable->end_time->format('l jS \\of F Y') }}</span>
                            </li>
                        @else
                        <li class="list-group-item">
                            <strong>{{ $firstTimetable->start_time->format('l') }}</strong>
                            {{ $firstTimetable->start_time->format('h:i A') }} -
                            {{ $firstTimetable->end_time->format('h:i A') }}
                            @if($activity->timetables->count() > 1)
                                <span class="text-muted">+ {{ $activity->timetables->count() - 1 }} more days</span>
                            @endif
                        </li>
                        @if($activity->timetables->count() == 1)
                        <li class="list-group-item list-group-item-info">
                            <img src="{{ asset('images/animat-lightbulb-color.gif') }}" alt="" style="width:20px;position:relative;top:-1px;"/>
                            Today Only</li>
                        @endif
                        @endif
                    </ul>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endforeach