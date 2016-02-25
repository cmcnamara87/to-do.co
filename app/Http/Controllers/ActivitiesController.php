<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Feature;
use App\Timetable;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActivitiesController extends Controller
{
    public function index() {
        $day = Carbon::now();
        $activities = Activity::whereHas('timetables', function($query) use ($day) {
            $query->where('end_time', '>=', $day);
        })->with(['timetables' => function ($q) {
            $q->where('end_time', '>=', Carbon::now());
        }])->get();

        // sort them by some magical formula (its the number of days)
        $activities = $activities->sortByDesc(function ($activity, $key) use ($day) {

//            $this->info($activity->title);


            $nextTimetable = $activity->timetables[0];
            $end = $day->copy()->endOfDay();
            $score = 0;
            // is it on today?
            if ($nextTimetable->start_time->lte($end) && $nextTimetable->end_time->gte($day)) {
//                $this->info('- Today 10');
                $score += 10;
            }
            // is it only on today, big bump
            // is it only on today, big bump
//            $this->info(' - Count ' . json_encode($activity->timetables));
//            $this->info(' - Count ' . json_encode($activity->categories));
            if ($activity->timetables->count() == 1 &&
                $nextTimetable->start_time->diffInHours($nextTimetable->end_time) <= 24
            ) {
//                $this->info('- Only Today 10');
                $score += 40;
                // is it starting soon
//                $nextTimetable->start_time->diffInHours(Carbon::)
            }

            // is it in good categories
            $categoryNames = $activity->categories->lists('name')->all();
            // remove spaces!
            $categoryNames = array_map(function($name) {
                return trim($name);
            }, $categoryNames);
            $goodCategories = [
                "Sir Thomas Brisbane Planetarium",
                "Festivals",
                "LIVE",
                "Music and concert",
                "Brisbane Powerhouse",
                "Riverstage",
                "food and drink",
                "Brisbane Markets",
                "movies"
            ];
            $goodCategoryScore = 0;
            foreach($goodCategories as $goodCategory) {
                if (in_array($goodCategory, $categoryNames)) {
//                    $this->info('- ' . $goodCategory . ' 10');
                    $goodCategoryScore += 20;
                }
            }
            $score += $goodCategoryScore;

            if(strpos($activity->description, ' eat ') !== false ||
                strpos($activity->description, ' food ') !== false
            ) {
//                $this->info('- Food 10');
                $score += 10;
            }
//            $this->info('total ' . $score);
            return $score;
        });
//        $activities = $activities->values()->take(10);

        return view('activities.index', compact('activities'));
    }

    public function show($activity) {
        return view('activities.show', compact('activity'));
    }
}
