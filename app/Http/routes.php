<?php

/**
 * Urls
 * / -> list of cities
 * /{city} -> just dump all activities
 * /{city}/{category} -> dump all activities in that category
 */

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//Route::get('sitemap', function(){
//
//    // create new sitemap object
//    $sitemap = App::make("sitemap");
//
//    // set cache key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean)
//    // by default cache is disabled
//    $sitemap->setCache('laravel.sitemap', 60);
//
//    // check if there is cached sitemap and build new only if is not
//    if (!$sitemap->isCached())
//    {
//        // add item to the sitemap (url, date, priority, freq)
//        $sitemap->add(URL::to('/'), \Carbon\Carbon::today(), '0.5', 'monthly');
//
//        $cities = \App\City::all();
//        $categories = \App\Category::all();
//        foreach($cities as $city) {
//            $sitemap->add(url("{$city->slug}"), \Carbon\Carbon::today(), 0.9, 'daily');
//            foreach($categories as $category) {
//                $sitemap->add(url("{$city->slug}/{$category->slug}"), \Carbon\Carbon::today(), 0.8, 'daily');
//            }
//        }
//
//        // get all posts from db
//        $activities = \App\Activity::orderBy('created_at', 'desc')->get();
//
//        // add every post to the sitemap
//        foreach ($activities as $activity)
//        {
//            $sitemap->add(url("activities/{$activity->slug}"), $activity->updated_at, 0.5, 'yearly');
//        }
//    }
//    // show your sitemap (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
//    return $sitemap->render('xml');
//});

//brisbane city council events	72	13	31		99	Search
//Route::get('/brisbane-city-council-events', function() {
//    $title = 'Brisbane City Council Events';
//    $description = 'Find all the Brisbane City Council Events on in Brisbane';
//    $when = 'today';
//    $sort = 'soon';
//    $start = \Carbon\Carbon::now();
//    $end = \Carbon\Carbon::now()->endOfDay();
//
//    $category = \App\Category::where('name', 'Brisbane City Council')->first();
//
//    $activities = \App\Activity::whereHas('timetables', function ($query) use ($start, $end) {
//        $query->where('end_time', '>=', $start); // not over yet
//        $query->where('start_time', '<=', $end); // but it starts before today is ova
//    })->whereHas('categories', function($query) use ($category) {
//        $query->where('id', $category->id);
//    })->with(['categories', 'timetables' => function ($q) use ($start) {
//        $q->where('end_time', '>=', $start);
//    }])->get();
//
//    return view('pages.show', compact('activities', 'title', 'when', 'description'));
//});

//things do brisbane city	80	14	0		99	Search

//Route::get('/categories', 'CategoriesController@index');
Route::resource('features', 'FeaturesController');
Route::get('/', 'FeaturesController@index');

Route::get('/wordpress', function() {
    $post = \App\Post::create([
        'post_content' => "this is my content",
        'post_title' => "new post title",
        'post_excerpt' => "my excertp...its hard to type"
    ]);
    $post->post_status = "draft";
    $post->save();
    dd($post);
});
Route::group(['middleware' => 'cors', 'prefix' => 'api'], function(){
    Route::get('users/{userId}/activities', function($userId) {
        // get activities already decided on
        $alreadyDecidedActivityIds = \App\Decision::where('user_id', $userId)
            ->lists('activity_id');

        $day = \Carbon\Carbon::today();

        // get hte timetables on today
        // ordered by, the ones ending the sooner
        $activityIds = \App\Timetable::where('end_time', '>=', \Carbon\Carbon::now())->orderBy('end_time', 'asc')->lists('activity_id');

        $activities = \App\Activity::whereHas('timetables', function($query) use ($day) {
            $query->where('end_time', '>=', $day);
        })->whereNotNull('image_url')->where('image_url', '!=', '')->whereNotIn('id', $alreadyDecidedActivityIds)->with(['timetables' => function ($q) {
            // havent ended yet
            $q->where('end_time', '>=', \Carbon\Carbon::now());
        }])->orderByRaw('FIELD(id,' . implode(',', $activityIds->toArray()) . ')')->paginate(5);
        return response()->json($activities);
    });
    Route::get('activities/{id}', function ($id) {
        $day = \Carbon\Carbon::today();
        $activity = \App\Activity::where('id', $id)
            ->whereHas('timetables', function($query) use ($day) {
            $query->where('end_time', '>=', $day);

        })->with(['timetables' => function ($q) {
            // havent ended yet
            $q->where('end_time', '>=', \Carbon\Carbon::now());
        }])->first();
        return response()->json($activity);
    });
    Route::put('users/{userId}/activities/{activityId}/done', function($userId, $activityId) {
        $decision = \App\Decision::where('user_id', $userId)->where('activity_id', $activityId)->first();
        $decision->decision = 3;
        $decision->save();
        return response()->json($decision);
    });
    Route::get('users/{userId}/calendar', function ($userId) {
        $likedActivityIds = \App\Decision::where('user_id', $userId)
            ->where('decision', 1)
            ->lists('activity_id')->unique();

        // all liked activities that havent ended
        $day = \Carbon\Carbon::today();
        $activities = \App\Activity::whereHas('timetables', function($query) use ($day) {
            $query->where('end_time', '>=', $day);
        })->whereIn('id', $likedActivityIds)->with(['timetables' => function ($q) {
            // havent ended yet
            $q->where('end_time', '>=', \Carbon\Carbon::now());
        }])->get();

        $calendar = $activities->reduce(function ($carry, $activity) {
            $startTimes = $activity->timetables->map(function ($timetable) {
                $startTime = $timetable->start_time;
                if($startTime->lte(\Carbon\Carbon::today())) {
                    return \Carbon\Carbon::today();
                }
                return $startTime;
            })->unique('timestamp');

            foreach($startTimes as $startTime) {
                $carry[$startTime->startOfDay()->timestamp][] = $activity;
            }
            return $carry;
        }, []);
        return $calendar;
    });
    Route::post('users', function () {
        $user = \App\User::create([]);
        return $user;
    });
    Route::post('users/{userId}/email', function ($userId) {
        $data = \Illuminate\Support\Facades\Input::only('email');
        $user = \App\User::find($userId);
        $user->fill($data);
        $user->save();
        return $user;
    });
    Route::post('users/{userId}/decisions', function ($userId) {
        $data = \Illuminate\Support\Facades\Input::all();
        // store the decision
        $data['user_id'] = $userId;
        $decision = \App\Decision::create($data);
        return $decision;
    });
});


//Route::get('/', 'CitiesController@index');
Route::get('{city}', 'CitiesController@show');
Route::get('activities/{activity}', 'ActivitiesController@show');
//Route::get('{city}/{category}', 'CitiesCategoriesController@show');


//Route::get('/things-to-do-in-brisbane', 'FeaturesController@index');
//Route::resource('activities', 'ActivitiesController');
//Route::resource('features', 'FeaturesController');
//
//Route::get('/brisbane/{when}/{sort}', 'ActivitiesController@brisbane');
//Route::get('/brisbane/{categories}/{when}/{sort}', 'CategoriesController@show');
//Route::get('/brisbane/this-weekend/soon', 'ActivitiesController@brisbaneThisWeekendSoon');
//Route::get('/brisbane/this-weekend/cool', 'ActivitiesController@brisbaneThisWeekendCool');
//
//Route::get('/brisbane/today/cool', 'ActivitiesController@brisbaneTodayCool');
//Route::get('/brisbane/today/soon', 'ActivitiesController@brisbaneTodaySoon');
//
//Route::get('/brisbane/tomorrow/cool', 'ActivitiesController@brisbaneTomorrowCool');
//Route::get('/brisbane/tomorrow/soon', 'ActivitiesController@brisbaneTomorrowSoon');

//Route::get('/categories/{categories}', 'CategoriesController@show');
//Route::get('/categories', 'CategoriesController@index');
//
//Route::get('/{activities}/{when?}', 'ActivitiesController@show');

// Jaaxy Routes
// fun things to do in brisbane	75	13	108		92	Search
//brisbane sporting events	40	7	35		97	Search

//brisbane events july	32	6	20		97	Search
//brisbane events december	24	5	9		97	Search
//brisbane event venues	40	7	21		97	Search
//brisbane events calendar	96	17	66		95	Search
//brisbane entertainment centre	1200	205	192		81	Search
//brisbane this weekend	336	58	186		83	Search
//brisbane australia tourism	32	6	38		97	Search
//brisbane tourist information	96	17	68		95	Search
//	art activities for kids	679	116	198		85	Search
//activities for senior citizens	3677	626	0		92
//brisbane things to do	171	30	135		96	Search
//brisbane day trip	32	6	68		96	Search
//brisbane places visit	32	6	4		97	Search
//brisbane city attractions	64	11	42		96	Search
//brisbane things do	171	30	4		95	Search
//	things to do in brisbane australia	213	37	77		91	Search
//things do brisbane easter	32	6	0		100	Search
//things do brisbane australia	213	37	0		97	Search
//	thing do brisbane australia	24	5	0		100	Search
//	attraction brisbane australia	32	6	22		97	Search
//sightseeing brisbane	72	13	140		88	Search
//place visit brisbane	24	5	10		97	Search
//places go brisbane	64	11	4		96	Search
//places interest brisbane	32	6	2		97	Search
//things do brisbane	5539	942	26		88	Search
//brisbane museum modern art	32	6	3		100	Search
//gallery modern art brisbane australia	32	6	7		98	Search
//gallery modern art brisbane	96	17	12		99	Search


/**
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
