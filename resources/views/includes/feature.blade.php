
<div class="row">
    <div class="col-sm-8 ">
        <ul class="list-group">
            @foreach($feature->activities as $activity)
                <li class="list-group-item" @if(!$activity->timetables->count()) style="opacity:0.5;text-decoration:line-through;" @endif>
                    <div class="media">
                        <div class="pull-left">
                            <img src="{{ asset($activity->image_url) }}" alt="{{ $activity->title }}" style="width:100px;"/>
                        </div>
                        <div class="media-body">
                            <h4><a href="{{ url("{$activity->slug}") }}">{{ $activity->title }}</a></h4>
                            <p>
                                {!! str_limit(strip_tags($activity->description), 150, '...') !!}
                            </p>
                            <ul>
                                @foreach($activity->timetables as $timetable)
                                <li>
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
                </li>
            @endforeach
        </ul>
    </div>
</div>