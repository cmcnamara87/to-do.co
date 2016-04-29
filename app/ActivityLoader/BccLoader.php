<?php
/**
 * Created by PhpStorm.
 * User: craig
 * Date: 21/04/16
 * Time: 8:32 PM
 */

namespace App\ActivityLoader;

use App\Activity;
use App\Category;
use App\City;
use App\Timetable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BccLoader implements ActivityLoader {
    public function load() {
        $eventUrls = [
            "Classes and workshops" => "http://www.trumba.com/calendars/type.rss?filterview=classses",
            "Fitness and strength events" => "http://www.trumba.com/calendars/type.rss?filterview=Fitness&mixin=6887832c6817012c7829352c812762",
            "Business events" => "http://www.trumba.com/calendars/BiB.rss",
            "Music and concert events" => "http://www.trumba.com/calendars/type.rss?filterview=Music&filter1=_178867_&filterfield1=21859",
            "Brisbane Botanic Gardens events" => "http://www.trumba.com/calendars/brisbane-botanic-gardens.rss",
            //"Brisbane Festival 2015 events" => "http://www.trumba.com/calendars/brisbane-festival.rss",
            "Arts, crafts and culture events" => "http://www.trumba.com/calendars/type.rss?filterview=arts&filter1=_171831_178893_&filterfield1=21859",
            "Brisbane Markets" => "http://www.trumba.com/calendars/type.rss?filterview=Markets&filter1=_178869_&filterfield1=21859",
            "Library events" => "http://www.trumba.com/calendars/libraries.rss",
            "Movies" => "http://www.trumba.com/calendars/type.rss?filterview=movies&filter1=_178865_&filterfield1=21859",
//            "Visible ink events" => "http://www.trumba.com/calendars/visble-ink.rss",
//            "Teen events" => "http://www.trumba.com/calendars/brisbane-kids.rss?filterview=teens",
            "Southbank Parklands events" => "http://www.trumba.com/calendars/south-bank.rss?filterview=southbank&filter4=_464155_&filterfield4=22542",
            "Sir Thomas Brisbane Planetarium events" => "http://www.trumba.com/calendars/planetarium.rss",
            "Riverstage events" => "http://www.trumba.com/calendars/brisbane-riverstage.rss",
            "Museum of Brisbane events" => "http://www.trumba.com/calendars/mob.rss",
            // "King George Square events" => "http://www.trumba.com/calendars/type.rss?filterview=Fitness&mixin=6887832c6817012c7829352c812762",
//            "Kids aged 6 to 12 events" => "http://www.trumba.com/calendars/brisbane-kids.rss?filterview=kids_6_12",
//            "Infants and toddlers events" => "http://www.trumba.com/calendars/brisbane-kids.rss?filterview=infants_toddlers",
            "Green events" => "http://www.trumba.com/calendars/green-events.rss?filterview=green_events",
//            "GOLD program events" => "http://www.trumba.com/calendars/gold.rss?filterview=gold",
//            "GOLD n' Kids events" => "http://www.trumba.com/calendars/gold-n-kids.rss",
            "Festivals" => "http://www.trumba.com/calendars/type.rss?filterview=festivals&filter1=_178868_&filterfield1=21859",
            "Family events" => "http://www.trumba.com/calendars/audience-brisbane.rss?filterview=family&filter1=_178891_&filterfield1=21859",
            "Chill Out events" => "http://www.trumba.com/calendars/chill-out.rss",
            "Brisbane Powerhouse events" => "http://www.trumba.com/calendars/brisbane-powerhouse.rss",
            "Brisbane Parks events" => "http://www.trumba.com/calendars/brisbane-events-rss.rss?filterview=parks",
            "Brisbane City Council events" => "http://www.trumba.com/calendars/brisbane-city-council.rss",
            "City Hall events" => "http://www.trumba.com/calendars/city-hall.rss?filterview=city-hall&filter4=_266279_&filterfield4=22542",
            "Active parks events" => "http://www.trumba.com/calendars/active-parks.rss",
            "LIVE events" => "http://www.trumba.com/calendars/LIVE.rss"

        ];

        foreach ($eventUrls as $categoryName => $url) {
            Log::info($categoryName);
            $this->loadBcc($categoryName, $url);
        }
    }

    function loadBcc($categoryName, $url)
    {
        $category = Category::firstOrCreate([
            "name" => trim(str_replace("events", "", $categoryName))
        ]);


        // south bank feed
        $xml = simplexml_load_file($url);
        $activityIds = [];

        foreach ($xml->channel->item as $item) {
            Log::info($item->title);

            // namespaces
            $namespaces = $item->getNameSpaces(true);
            $trumba = $item->children($namespaces['x-trumba']);
            if(isset($namespaces['xCal'])) {
                $xcal = $item->children($namespaces['xCal']);
            }

            // prices
            $image_url = '';
            $price = -1;
            $value = -1;
            foreach ($trumba->customfield as $customField) {
                if ($customField->attributes()->name == "Event image") {
                    $image_url = (string)$customField;
                }
                if ($customField->attributes()->name == "Cost") {
                    $priceString = (string)$customField;
                    Log::info($priceString);
                    if($priceString == "Free") {
                        $price = 0;
                        $value = 0;
                    }
                }
            }

            // title and description
            $title = (string)$item->title;
            if(isset($xcal)) {
                $description = (string)$xcal->description;
            } else {
                $description = '';
            }

            // dont process anything with these words
            $stopWords = [
                "senior",
                "bubs",
                "babies",
                "50 plus",
                "children"
            ];
            foreach($stopWords as $stopWord) {
                if(strpos ($title, $stopWord) !== false ||
                    strpos ($description, $stopWord) !== false) {
                    continue 2;
                }
            }

            Log::info($trumba->weblink);

            // get the price
            $matches = [];

            if($price < 0) {
                preg_match ( '/\$(\d+\.\d+)/', (string)$item->description, $matches );
                if(count($matches)) {
                    $price = $matches[1];
                    $value = $matches[1];
                }
            }

            $city = City::firstOrCreate([
                "name" => 'Brisbane'
            ]);
            $activity = Activity::firstOrCreate(['title' => $title]);
            $activity->fill([
                "description" => $description,
                "weblink" => (string)$item->link,
                "image_url" => $image_url,
                "price" => $price,
                "value" => $value,
                "city_id" => $city->id
            ]);
            $activity->save();
            if($price == 0) {
                $freeCategory = Category::firstOrCreate([
                    "name" => "Free"
                ]);
                Log::info("is free");
                $activity->categories()->sync([$freeCategory->id], false);
            }
            else if ($price > 0 && $price <= 20) {
                $cheapCategory = Category::firstOrCreate([
                    "name" => "Cheap"
                ]);
                Log::info("is cheap");
                $activity->categories()->sync([$cheapCategory->id], false);
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

        $goodCategories = [
            "Festivals",
            "LIVE",
            "Music and concert",
            "Brisbane Powerhouse",
            "Riverstage",
            "Brisbane Markets",
            "movies"
        ];
        if (in_array($categoryName, $goodCategories)) {
            $featuredCategory = Category::firstOrCreate([
                'name' => 'Featured'
            ]);
            $featuredCategory->activities()->sync($activityIds, false);
        }
    }

}