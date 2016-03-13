@foreach (array_chunk($activities->all(), 3) as $activitiesRow)
    <div class="row">
        @foreach ($activitiesRow as $activity)
            <div class="col-sm-4">
                <div style="margin-bottom: 20px;">
                    {{--<img src="{{ asset($activity->image_url) }}" alt="{{ $activity->title }}" style="width:100%;position:absolute;top:0;z-index:1"/>--}}

                    <div style="color: white;padding:30px;background-size: cover;background-image:
    linear-gradient(
      rgba(0, 0, 0, 0.4),
      rgba(0, 0, 0, 0.6)
    ),
    url({{ asset($activity->image_url) }});">
                        <div>
                            <p class="text-capitalize">
                                {{ $activity->categories->first()->name }}
                            </p>
                            <p style="color: white;font-weight: bold;opacity: 0.8;;">
                                @if($activity->price == 0)
                                    Free
                                @elseif($activity->price > 0)
                                    ${{ $activity->price }}
                                    @if($activity->price != $activity->value)
                                        was ${{ $activity->value }}
                                    @endif
                                @endif
                            </p>
                        </div>

                        <h3 style="margin-top:100px;font-size:20px;"><a style="color:inherit;" href="{{ url("{$activity->slug}") }}">{{ $activity->title }}</a></h3>
                        <p style="opacity: 0.9;margin-bottom: 15px;">
                            {{ strip_tags($activity->description) }}
                        </p>

                        <button style="padding:8px 25px;margin-bottom: 20px;" class="btn btn-info">View Activity</button>

                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endforeach

