<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Category;
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
    public function brisbane($when, $sort) {
        if($when == 'this-weekend') {
            $start = Carbon::parse('Saturday');
            $end = Carbon::parse('Sunday')->endOfDay();
        } else if($when == 'tomorrow') {
            $start = Carbon::tomorrow();
            $end = Carbon::tomorrow()->endOfDay();
        } else {
            $start = Carbon::today();
            $end = Carbon::today()->endOfDay();
        }
        $activities = $this->getActivities($start, $end, $sort);
        // get the categories
        $categories = Category::all();
        return view('activities.index', compact('activities', 'sort', 'when', 'categories'));
    }
    public function brisbaneThisWeekendSoon() {
        return $this->brisbane('this-weekend', 'soon');
    }
    public function brisbaneThisWeekendCool() {
        return $this->brisbane('this-weekend', 'cool');
    }
    public function brisbaneTomorrowSoon() {
        return $this->brisbane('tomorrow', 'soon');
    }
    public function brisbaneTomorrowCool() {
        return $this->brisbane('tomorrow', 'cool');
    }
    public function brisbaneTodaySoon() {
        return $this->brisbane('today', 'soon');
    }
    public function brisbaneTodayCool() {
        return $this->brisbane('today', 'cool');
    }

    public function cool($activities) {
        return $activities->sortByDesc(function ($activity, $key) {
            $nextTimetable = $activity->timetables[0];
            $score = 0;

            // is it only on today, big bump
            // how many days is it on?
            $daysOn = array_reduce($activity->timetables->all(), function($carry, $timetable) {
                $carry += max($timetable->start_time->diffInDays($timetable->end_time), 1);
                return $carry;
            }, 0);
            // 30 for 1 day, 20 for 2 days, 10 for 3 days, 0 for any more days
            $score += max(0, (30 - (10 * ($daysOn - 1))));

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
                    $score += 10;
                }
            }
            $score += $goodCategoryScore;

            if(strpos($activity->description, ' eat ') !== false ||
                strpos($activity->description, ' food ') !== false
            ) {
                $score += 10;
            }
            return $score;
        });
    }

    public function getActivities(Carbon $start, Carbon $end, $sort) {
        $activities = Activity::whereHas('timetables', function ($query) use ($start, $end) {
            $query->where('end_time', '>=', $start); // not over yet
            $query->where('start_time', '<=', $end); // but it starts before today is ova
        })->with(['categories', 'timetables' => function ($q) use ($start) {
            $q->where('end_time', '>=', $start);
        }])->get();
        return $this->$sort($activities);
    }

    public function soon($activities) {
        return $activities->sortBy(function ($activity) {
            $nextTimetable = $activity->timetables[0];
            return Carbon::now()->diffInMinutes($nextTimetable->start_time);
        });
    }

    public function index()
    {
        $day = Carbon::now();
        $activities = Activity::whereHas('timetables', function ($query) use ($day) {
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
            $categoryNames = array_map(function ($name) {
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
            foreach ($goodCategories as $goodCategory) {
                if (in_array($goodCategory, $categoryNames)) {
//                    $this->info('- ' . $goodCategory . ' 10');
                    $goodCategoryScore += 20;
                }
            }
            $score += $goodCategoryScore;

            if (strpos($activity->description, ' eat ') !== false ||
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

    public function show($activity)
    {
        return view('activities.show', compact('activity'));
    }
}
