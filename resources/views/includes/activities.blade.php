@foreach (array_chunk($activities->all(), 4) as $activitiesRow)
    <div class="row">
        @foreach ($activitiesRow as $activity)
            <div class="col-sm-3">
                <div class="panel panel-default">
                    <div>
                        @if(isset($activity->score))
                            <strong>{{ $activity->score }}</strong>
                        @endif
                        <a href="{{ url("{$activity->slug}") }}">
                            <img src="{{ asset($activity->image_url) }}" alt="{{ $activity->title }}" style="width:100%;"/>
                        </a>
                    </div>
                    <div class="panel-body">
                        <h3><a href="{{ url("{$activity->slug}") }}">{{ $activity->title }}</a></h3>
                        <h4>
                            @if($activity->price == 0)
                                Free
                            @elseif($activity->price > 0)
                                ${{ $activity->price }}
                                @if($activity->price != $activity->value)
                                was ${{ $activity->value }}
                                @endif
                            @endif
                        </h4>
                        <ul>
                            @foreach($activity->categories as $category)
                                <li>{{ $category->name }}</li>
                            @endforeach
                        </ul>

                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endforeach

