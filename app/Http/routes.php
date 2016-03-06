<?php

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
Route::get('sitemap', function(){

    // create new sitemap object
    $sitemap = App::make("sitemap");

    // set cache key (string), duration in minutes (Carbon|Datetime|int), turn on/off (boolean)
    // by default cache is disabled
    $sitemap->setCache('laravel.sitemap', 60);

    // check if there is cached sitemap and build new only if is not
    if (!$sitemap->isCached())
    {
        // add item to the sitemap (url, date, priority, freq)
        $sitemap->add(URL::to('/'), \Carbon\Carbon::today(), '1.0', 'daily');
        $sitemap->add(URL::to('activities'), \Carbon\Carbon::today(), '0.9', 'daily');


        // get all posts from db
        $activities = \App\Activity::orderBy('created_at', 'desc')->get();

        // add every post to the sitemap
        foreach ($activities as $activity)
        {
            // add item with images
            $images = [[
                'url' => asset($activity->image_url),
                'title' => $activity->title,
                'caption' => $activity->description
            ]];
            $sitemap->add(url("{$activity->slug}"), $activity->updated_at, 0.5, 'yearly', $images);
        }
    }
    // show your sitemap (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
    return $sitemap->render('xml');
});

//brisbane city council events	72	13	31		99	Search
Route::get('/brisbane-city-council-events', function() {
    $title = 'Brisbane City Council Events';
    $description = 'Find all the Brisbane City Council Events on in Brisbane';
    $when = 'today';
    $sort = 'soon';
    $start = \Carbon\Carbon::now();
    $end = \Carbon\Carbon::now()->endOfDay();

    $category = \App\Category::where('name', 'Brisbane City Council')->first();

    $activities = \App\Activity::whereHas('timetables', function ($query) use ($start, $end) {
        $query->where('end_time', '>=', $start); // not over yet
        $query->where('start_time', '<=', $end); // but it starts before today is ova
    })->whereHas('categories', function($query) use ($category) {
        $query->where('id', $category->id);
    })->with(['categories', 'timetables' => function ($q) use ($start) {
        $q->where('end_time', '>=', $start);
    }])->get();

    return view('pages.show', compact('activities', 'title', 'when', 'description'));
});

//things do brisbane city	80	14	0		99	Search


Route::get('/', 'CategoriesController@index');
Route::get('/things-to-do-in-brisbane', 'FeaturesController@index');
Route::resource('activities', 'ActivitiesController');
Route::resource('features', 'FeaturesController');

Route::get('/brisbane/{when}/{sort}', 'ActivitiesController@brisbane');
Route::get('/brisbane/{categories}/{when}/{sort}', 'CategoriesController@show');
//Route::get('/brisbane/this-weekend/soon', 'ActivitiesController@brisbaneThisWeekendSoon');
//Route::get('/brisbane/this-weekend/cool', 'ActivitiesController@brisbaneThisWeekendCool');
//
//Route::get('/brisbane/today/cool', 'ActivitiesController@brisbaneTodayCool');
//Route::get('/brisbane/today/soon', 'ActivitiesController@brisbaneTodaySoon');
//
//Route::get('/brisbane/tomorrow/cool', 'ActivitiesController@brisbaneTomorrowCool');
//Route::get('/brisbane/tomorrow/soon', 'ActivitiesController@brisbaneTomorrowSoon');

Route::get('/categories/{categories}', 'CategoriesController@show');
Route::get('/categories', 'CategoriesController@index');

Route::get('/{activities}/{when?}', 'ActivitiesController@show');

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
