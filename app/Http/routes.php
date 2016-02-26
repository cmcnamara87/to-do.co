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

Route::get('/', 'ActivitiesController@brisbaneTodayCool');
Route::get('/things-to-do-in-brisbane', 'FeaturesController@index');
Route::resource('activities', 'ActivitiesController');
Route::resource('features', 'FeaturesController');

Route::get('/brisbane/today/cool', 'ActivitiesController@brisbaneTodayCool');
Route::get('/brisbane/today/soon', 'ActivitiesController@brisbaneTodaySoon');

Route::get('/categories/{categories}', 'CategoriesController@show');
Route::get('/categories', 'CategoriesController@index');

Route::get('/{activities}', 'ActivitiesController@show');
/*
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
