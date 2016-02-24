<?php

namespace App\Console\Commands;

use App\Activity;
use App\Category;
use App\Feature;
use App\Jobs\CreateActivity;
use App\Timetable;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $this->info('Loading activites');
        $this->loadMovies();
        $this->goGroupon();
        $eventUrls = [
            "Fitness and strength events" => "http://www.trumba.com/calendars/type.rss?filterview=Fitness&mixin=6887832c6817012c7829352c812762",
            "Business events" => "http://www.trumba.com/calendars/BiB.rss",
            "Music and concert events" => "http://www.trumba.com/calendars/type.rss?filterview=Music&filter1=_178867_&filterfield1=21859",
            "Brisbane Botanic Gardens events" => "http://www.trumba.com/calendars/brisbane-botanic-gardens.rss",
            //"Brisbane Festival 2015 events" => "http://www.trumba.com/calendars/brisbane-festival.rss",
            "Arts, crafts and culture events" => "http://www.trumba.com/calendars/type.rss?filterview=arts&filter1=_171831_178893_&filterfield1=21859",
            "Brisbane Markets" => "http://www.trumba.com/calendars/type.rss?filterview=Markets&filter1=_178869_&filterfield1=21859",
            "Library events" => "http://www.trumba.com/calendars/libraries.rss",
            "Movies" => "http://www.trumba.com/calendars/type.rss?filterview=movies&filter1=_178865_&filterfield1=21859",
            "Visible ink events" => "http://www.trumba.com/calendars/visble-ink.rss",
            "Teen events" => "http://www.trumba.com/calendars/brisbane-kids.rss?filterview=teens",
            "Southbank Parklands events" => "http://www.trumba.com/calendars/south-bank.rss?filterview=southbank&filter4=_464155_&filterfield4=22542",
            "Sir Thomas Brisbane Planetarium events" => "http://www.trumba.com/calendars/planetarium.rss",
            "Riverstage events" => "http://www.trumba.com/calendars/brisbane-riverstage.rss",
            "Museum of Brisbane events" => "http://www.trumba.com/calendars/mob.rss",
            "LIVE events" => "http://www.trumba.com/calendars/LIVE.rss",
            // "King George Square events" => "http://www.trumba.com/calendars/type.rss?filterview=Fitness&mixin=6887832c6817012c7829352c812762",
            "Kids aged 6 to 12 events" => "http://www.trumba.com/calendars/brisbane-kids.rss?filterview=kids_6_12",
            "Infants and toddlers events" => "http://www.trumba.com/calendars/brisbane-kids.rss?filterview=infants_toddlers",
            "Green events" => "http://www.trumba.com/calendars/green-events.rss?filterview=green_events",
            "GOLD program events" => "http://www.trumba.com/calendars/gold.rss?filterview=gold",
            "GOLD n' Kids events" => "http://www.trumba.com/calendars/gold-n-kids.rss",
            "Festivals" => "http://www.trumba.com/calendars/type.rss?filterview=festivals&filter1=_178868_&filterfield1=21859",
            "Family events" => "http://www.trumba.com/calendars/audience-brisbane.rss?filterview=family&filter1=_178891_&filterfield1=21859",
            "Chill Out events" => "http://www.trumba.com/calendars/chill-out.rss",
            "Brisbane Powerhouse events" => "http://www.trumba.com/calendars/brisbane-powerhouse.rss",
            "Brisbane Parks events" => "http://www.trumba.com/calendars/brisbane-events-rss.rss?filterview=parks",
            "Brisbane City Council events" => "http://www.trumba.com/calendars/brisbane-city-council.rss",
            "Classes and workshops" => "http://www.trumba.com/calendars/type.rss?filterview=classses",
            "City Hall events" => "http://www.trumba.com/calendars/city-hall.rss?filterview=city-hall&filter4=_266279_&filterfield4=22542",
            "Active parks events" => "http://www.trumba.com/calendars/active-parks.rss"

        ];

        foreach ($eventUrls as $categoryName => $url) {
            $this->info($categoryName);
            $this->go($categoryName, $url);
        }

        $this->createFeaturedForDay(Carbon::today());
    }


    function go($categoryName, $url)
    {
        $category = Category::firstOrCreate([
            "name" => str_replace("events", "", $categoryName)
        ]);

        // south bank feed
        $xml = simplexml_load_file($url);
        $activityIds = [];

        foreach ($xml->channel->item as $item) {
            $this->info($item->title);

            // namespaces
            $namespaces = $item->getNameSpaces(true);
            $trumba = $item->children($namespaces['x-trumba']);
            $xcal = $item->children($namespaces['xCal']);

            $image_url = '';
            foreach ($trumba->customfield as $customField) {
                if ($customField->attributes()->name == "Event image") {
                    $image_url = (string)$customField;
                }
            }

            $title = (string)$item->title;
            $description = (string)$xcal->description;
            $this->info($trumba->weblink);

            $activity = Activity::where('title', $title)->first();
            if (!$activity) {
                $activity = Activity::create([
                    "title" => $title,
                    "description" => $description,
                    "weblink" => (string)$item->link,
                    "image_url" => $image_url
                ]);
            }
            $activityIds[] = $activity->id;

            $timezone = 'Australia/Brisbane';
            $startTime = Carbon::parse($trumba->localstart, $timezone);
            $endTime = Carbon::parse($trumba->localend, $timezone);

            $timetable = Timetable::firstOrCreate([
                "activity_id" => $activity->id,
                "start_time" => $startTime,
                "end_time" => $endTime
            ]);
            Log::info('Adding tag ' . $category->name . ' for ' . $activity->title);
            // save it
        }
        $category->activities()->sync($activityIds, false);
    }

    function createFeaturedForDay(Carbon $day)
    {
        $previousFeaturedActivityIds = DB::table('activity_feature')->lists('activity_id');
        $activities = Activity::whereHas('timetables', function($query) use ($day, $previousFeaturedActivityIds) {
            $end = $day->copy()->endOfDay();
            $query->where('start_time', '<=', $end);
            $query->where('end_time', '>=', $day);
            $query->whereNotIn('activity_id', $previousFeaturedActivityIds);
        })->get();

        // sort them by some magical formula (its the number of days)
        $activities = $activities->sortBy(function ($activity, $key) use ($day) {
            $score = array_reduce($activity->timetables->all(), function ($carry, $timetable) {
                $diffInDays = $timetable->start_time->diff($timetable->end_time)->days;
                $carry += max($diffInDays, 1);
                return $carry;
            }, 0);
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
    }

    private function goGroupon()
    {
        $brisbaneGrouponUrl = "https://partner-int-api.groupon.com/deals.json?country_code=AU&tsToken=IE_AFF_0_200012_212556_0&division_id=brisbane&offset=0&limit=100";
        $groupon = json_decode(@file_get_contents($brisbaneGrouponUrl));
        $activityIds = array_map(function($deal) {
            $activity = Activity::firstOrCreate(['title' => $deal->newsletterTitle]);
            $activity->fill([
                "description" => $deal->highlightsHtml,
                "weblink" => $deal->dealUrl,
                "image_url" => $deal->largeImageUrl
            ]);
            $activity->save();

            $this->info('created activity ' . $activity->title);

            $start = Carbon::parse($deal->startAt);
            $end = Carbon::parse($deal->endAt);
            $timetable = Timetable::firstOrCreate([
                "activity_id" => $activity->id,
                "start_time" => $start,
                "end_time" => $end
            ]);

            // do category
            $categoryIds = array_reduce($deal->tags, function($carry, $tag) {
                // fix up some weird groupon syntax e.g. RESTAURANT1 as the tag
                $noNumbers = preg_replace('/\d/', '', $tag->name);
                $cleanName = str_replace('_', ' ', $noNumbers);
                $categoryName = strtolower($cleanName);
                if($categoryName == "australian") {
                    return $carry;
                }
                $category = Category::firstOrCreate([
                    "name" => $categoryName
                ]);
                $carry[] = $category->id;
                return $carry;
            }, []);
            $activity->categories()->sync($categoryIds, false);

            return $activity->id;
        }, $groupon->deals);
    }

    private function loadMovies()
    {
        // movies
        // just get the movies from indro
        $json = json_decode(@file_get_contents("http://moviesowl.com/api/v1/cinemas/39/movies"));
        $category = Category::firstOrCreate([
            "name" => "movies"
        ]);

        $activityIds = array_map(function($movie) use ($category) {
            $fakeSlug = preg_replace('/[^a-z\d]/i', '-', $movie->title);
            $activity = Activity::firstOrCreate(['title' => "Watch " . $movie->title . " at the Cinemas"]);
            $activity->fill([
                "description" => $movie->synopsis,
                "weblink" => "http://moviesowl.com/movies/$fakeSlug/Brisbane/today",
                "image_url" => "http://moviesowl.com/{$movie->wide_poster}"
            ]);
            $activity->save();

            $this->info('activity ' . $activity->title);
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
