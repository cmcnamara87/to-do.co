<?php
/**
 * Created by PhpStorm.
 * User: craig
 * Date: 22/04/16
 * Time: 5:01 PM
 */

namespace App\ActivityLoader;

use App\City;
use App\Activity;
use App\Category;
use App\Timetable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MoviesOwlLoader implements ActivityLoader {

    public function load() {
        $this->loadMovies();
    }

    private function loadMovies()
    {
        // movies
        // just get the movies from indro
        $json = json_decode(@file_get_contents("http://moviesowl.com/api/v1/cinemas/39/movies"));
        $category = Category::firstOrCreate([
            "name" => "movies"
        ]);

        $activityIds = array_map(function ($movie) use ($category) {
            $fakeSlug = preg_replace('/[^a-z\d]/i', '-', $movie->title);
            $activity = Activity::firstOrCreate(['title' => $movie->title . " at the Cinemas"]);
            $city = City::firstOrCreate([
                "name" => 'Brisbane'
            ]);
            $activity->fill([
                "description" => $movie->synopsis,
                "weblink" => "http://moviesowl.com/movies/$fakeSlug/Brisbane/today",
                "image_url" => "http://moviesowl.com/{$movie->wide_poster}",
                "price" => -1,
                "value" => -1,
                "city_id" => $city->id
            ]);
            $activity->save();

            Log::info('activity ' . $activity->title);
            $timetable = Timetable::firstOrCreate([
                "activity_id" => $activity->id,
                "start_time" => Carbon::today(),
                "end_time" => Carbon::parse("next thursday")
            ]);
            Log::info('Adding tag ' . $category->name . ' for ' . $activity->title);
            // save it
            return $activity->id;
        }, $json->data);
        $category->activities()->sync($activityIds, false);

    }

}