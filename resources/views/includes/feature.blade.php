
<div>
    <div>
        <ul class="list-unstyled">
            @foreach($feature->activities as $activity)
            <li @if(\Carbon\Carbon::now()->gte($activity->timetables->last()->end_time)) style="opacity:0.5;text-decoration:line-through;" @endif>
                <div class="media" style="border-bottom: 1px solid #eee;margin-bottom: 20px;padding-bottom: 20px;">
                    <div class="pull-left">
                        <img src="{{ asset($activity->image_url) }}" alt="{{ $activity->title }}" style="width:50px;height:50px;"
                                class="img-circle"/>
                    </div>
                    <div class="media-body">
                        {{--<ul class="pull-right">--}}
                            {{--@foreach($activity->categories as $category)--}}
                                {{--<li>{{ $category->name }}</li>--}}
                            {{--@endforeach--}}
                        {{--</ul>--}}

                        <h4 style="margin:0;"><a style="color:#333;font-size:16px" href="{{ url("activities/{$activity->slug}") }}">{{ $activity->title }}</a></h4>

                        @foreach($activity->timetables->take(1) as $timetable)
                            <p class="text-muted" style="margin:0;">
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
                            </p>
                        @endforeach

                        @if($activity->price > 0)
                            <p class="text-muted" style="margin:0;">
                                ${{ $activity->price }}
                            </p>
                        @endif
                        @if($activity->price == 0)
                            <p class="text-muted" style="margin:0;">
                                Free
                            </p>
                        @endif

                        {{--<p>--}}
                            {{--{!! str_limit(strip_tags($activity->description), 150, '...') !!}--}}
                        {{--</p>--}}

                        <ul>
                            @if(!$activity->timetables->count())
                            <li>
                                No more sessions.
                            </li>
                            @endif

                        </ul>
                    </div>
                </div>
            </li>
            @endforeach
            @if($feature->activities->count() < 10)
            <li>
               {{ 10 - $feature->activities->count() }} activities expired.
                {{--<a href="{{ url("activities") }}">See all the events on {{ $feature->date->format('l j F') }}</a>--}}
            </li>
            @endif
        </ul>
    </div>
</div>