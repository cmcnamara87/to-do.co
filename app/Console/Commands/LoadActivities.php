<?php

namespace App\Console\Commands;

use App\Activity;
use App\ActivityLoader\BccLoader;
use App\ActivityLoader\GrouponLoader;
use App\ActivityLoader\MoviesOwlLoader;
use App\Category;
use App\City;
use App\Feature;
use App\Jobs\CreateActivity;
use App\Timetable;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maknz\Slack\Facades\Slack;

class LoadActivities extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'todo:load';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Slack::send('Loading activities');
        $grouponLoader = new GrouponLoader();
        $grouponLoader->load();

        $bccLoader = new BccLoader();
        $bccLoader->load();

        $moviesOwlLoader = new MoviesOwlLoader();
        $moviesOwlLoader->load();

        // generate featured!
        // get all featured activtiies ids

        $day = Carbon::today();

        $previousFeaturedActivityIds = DB::table('activity_feature')->lists('activity_id');
        $activities = Activity::whereHas('timetables', function($query) use ($day, $previousFeaturedActivityIds) {
            $end = $day->copy()->addDays(3)->endOfDay();
            $query->where('start_time', '<=', $end);
            $query->where('end_time', '>=', $day);
            $query->whereNotIn('activity_id', $previousFeaturedActivityIds);
        })->get();

        // sort them by starting today
        $activities = $activities->sortBy(function ($activity, $key) {
            return $activity->timetables->get(0)->start_time->diff(Carbon::now())->days;
            return $score;
        });

        $activities = $activities->values()->take(10);


        $feature = Feature::where('date', $day)->first();
        if(!$feature) {
            $feature = Feature::firstOrCreate([
                "date" => $day
            ]);
        }
        $feature->activities()->delete();
        $feature->activities()->saveMany($activities);



        Slack::send('Publishing things to do');
        $activities->each(function($activity, $key) {
            Slack::send("$key. {$activity->title}");
        });


    }
}
