<?php

namespace App\Console\Commands;

use App\Activity;
use App\Jobs\CreateActivity;
use App\Timetable;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

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
        Activity::truncate();
        Timetable::truncate();

        $this->goGroupon();

        $eventUrls = [
            "Southbank Parklands events" => "http://www.trumba.com/calendars/south-bank.rss?filterview=south+bank&filter4=_464155_&filterfield4=22542",
            "Music and concert events" => "http://www.trumba.com/calendars/type.rss?filterview=Music&filter1=_178867_&filterfield1=21859",
            "Brisbane Botanic Gardens events" => "http://www.trumba.com/calendars/brisbane-botanic-gardens.rss",
            "Arts, crafts and culture events" => "http://www.trumba.com/calendars/type.rss?filterview=arts&filter1=_171831_178893_&filterfield1=21859",
            "Fitness and strength events" => "http://www.trumba.com/calendars/type.rss?filterview=Fitness&mixin=688783%2c681701%2c782935%2c812762",
            "Sir Thomas Brisbane Planetarium events" => "http://www.trumba.com/calendars/planetarium.rss",

            // BROKEN!
            // "King George Square events" => "http://www.trumba.com/calendars/type.rss?filterview=Fitness&mixin=688783%2c681701%2c782935%2c812762"
        ];

        foreach($eventUrls as $name => $url) {
            $this->info($name);
            $this->go($url);
        }




    }


    function go($url) {
        // south bank feed
        $xml = simplexml_load_file($url);
        foreach($xml->channel->item as $item) {
            $this->info($item->title);

            // namespaces
            $namespaces = $item->getNameSpaces(true);
            $trumba = $item->children($namespaces['x-trumba']);
            $xcal = $item->children($namespaces['xCal']);

            $image_url = '';
            foreach($trumba->customfield as $customField) {
                if($customField->attributes()->name == "Event image") {
                    $image_url = (string)$customField;
                }
            }

            $title = (string)$item->title;
            $description = (string)$xcal->description;
            $this->info($trumba->weblink);

            $activity = Activity::where('title', $title)->first();
            if(!$activity) {
                $activity = Activity::create([
                    "title" => $title,
                    "description" => $description,
                    "weblink" => (string)$item->link,
                    "image_url" => $image_url
                ]);
            }

            $timezone = 'Australia/Brisbane';
            $startTime = Carbon::parse($trumba->localstart, $timezone);
            $endTime = Carbon::parse($trumba->localend, $timezone);

            $timetable = Timetable::firstOrCreate([
                "activity_id" => $activity->id,
                "start_time" => $startTime,
                "end_time" => $endTime
            ]);
        }
    }

    private function goGroupon()
    {
        $brisbaneGrouponUrl = "https://partner-int-api.groupon.com/deals.json?country_code=AU&tsToken=IE_AFF_0_200012_212556_0&division_id=brisbane&offset=0&limit=20";
        $groupon = json_decode(@file_get_contents($brisbaneGrouponUrl));
        foreach ($groupon->deals as $deal) {

            $activity = Activity::create([
                "title" => $deal->newsletterTitle,
                "description" => $deal->highlightsHtml,
                "weblink" => $deal->dealUrl,
                "image_url" => $deal->largeImageUrl
            ]);

            $this->info('activity ' . $deal->title);

            $start = Carbon::parse($deal->startAt);
            $end = Carbon::parse($deal->endAt);
            $timetable = Timetable::firstOrCreate([
                "activity_id" => $activity->id,
                "start_time" => $start,
                "end_time" => $end
            ]);
        }
    }
}
