@foreach (array_chunk($activities->all(), 4) as $activitiesRow)
    <div class="row">
        @foreach ($activitiesRow as $activity)
            <div class="col-sm-3">
                <div class="panel panel-default">
                    <div>
                        @if(isset($activity->score))
                            <strong>{{ $activity->score }}</strong>
                        @endif
                        <a href="{{ url("{$activity->slug}/{$when}") }}">
                            <img src="{{ asset($activity->image_url) }}" alt="{{ $activity->title }}" style="width:100%;"/>
                        </a>
                    </div>
                    <div class="panel-body">
                        <h3><a href="{{ url("{$activity->slug}/{$when}") }}">{{ $activity->title }}</a></h3>
                        <div>
                            {!! str_limit(strip_tags($activity->description), 150, '...') !!}
                        </div>
                        <div>
                            <ul>
                                @foreach($activity->categories as $category)
                                <li>{{ $category->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <ul class="list-group">

                        <?php $firstTimetable = $activity->timetables[0]; ?>
                        <li class="list-group-item">
                            @if($firstTimetable->start_time->day == $firstTimetable->end_time->day)
                                <strong>{{ $firstTimetable->start_time->format('l j F Y') }}</strong>
                                {{ $firstTimetable->start_time->format('h:i A') }} -
                                {{ $firstTimetable->end_time->format('h:i A') }}
                            @else
                                <strong>{{ $firstTimetable->start_time->format('l j F Y') }}</strong>
                                {{ $firstTimetable->start_time->format('h:i A') }} -
                                <strong>{{ $firstTimetable->end_time->format('l j F Y') }}</strong>
                                {{ $firstTimetable->end_time->format('h:i A') }}
                            @endif
                        </li>
                        @if($activity->timetables->count() > 1)
                        <li class="list-group-item">
                            + {{ $activity->timetables->count() - 1 }} more times
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
@endforeach