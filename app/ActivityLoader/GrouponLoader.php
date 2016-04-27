<?php
/**
 * Created by PhpStorm.
 * User: craig
 * Date: 21/04/16
 * Time: 8:23 PM
 */

namespace App\ActivityLoader;

use App\Activity;
use App\Category;
use App\Timetable;
use Carbon\Carbon;
use App\City;
use Illuminate\Support\Facades\Log;

class GrouponLoader implements ActivityLoader {

    public function load() {
        $grouponCategories = [
//            "automotive",
//            "auto-and-home-improvement",
//            "accommodation",
//            "beauty-and-spas",
//            "baby-kids-and-toys",
//            "bed-and-breakfast-travel",
            "food-and-drink",
//            "collectibles",
//            "cabin-travel",
//            "health-and-fitness",
//            "electronics-goods",
//            "cruise-travel",
//            "home-improvement",
//            "entertainment-and-media", // its crappy self help books
//            "hotels",
//            "local-services", // its toilet repair
//            "food-and-drink-goods",
//            "resort-travel",
            "shopping",
//            "health-and-beauty-goods",
//            "tour-travel",
            "things-to-do",
//            "home-and-garden-goods",
//            "vacation-rental-travel",
// 	        "household-essentials",
// 	        "jewelry-and-watches",
// 	        "men",
// 	        "sports-and-outdoors",
// 	        "women"
        ];
        foreach ($grouponCategories as $grouponCategory) {
            $this->goGroupon($grouponCategory);
        }
    }

    private function goGroupon($grouponCategory)
    {
        if($grouponCategory == 'things-to-do') {
            $categoryName = 'Adventurous';
        } else {
            $categoryName = str_replace('-', ' ', $grouponCategory);
        }
        $category = Category::firstOrCreate([
            "name" => $categoryName
        ]);
        $limit = 200;
        $countryCode = 'AU';
        $gpnMediaId = '212556'; // https://partner-api.groupon.com/help/deal-api
        $brisbaneGrouponUrl = "https://partner-int-api.groupon.com/deals.json?country_code={$countryCode}&tsToken={$countryCode}_AFF_0_" . env('GPN_AFFILIATE_ID') . "_{$gpnMediaId}_0&division_id=brisbane&offset=0&limit=$limit&filters=category:$grouponCategory";
        $groupon = json_decode(@file_get_contents($brisbaneGrouponUrl));
        if (!isset($groupon->deals)) {
            Log::info('no deails found');
            return;
        }
        $activityIds = array_map(function ($deal) {
            $activity = Activity::firstOrCreate(['title' => $deal->newsletterTitle]);

            $price = $deal->priceSummary->price->amount / 100.0;
            $value = $deal->priceSummary->value->amount / 100.0;
            $city = City::firstOrCreate([
                "name" => 'Brisbane'
            ]);
            $activity->fill([
                "description" => $deal->highlightsHtml,
                "weblink" => $deal->dealUrl,
                "image_url" => $deal->largeImageUrl,
                "price" => $price,
                "value" => $value,
                "city_id" => $city->id
            ]);
            $activity->save();
            if ($price > 0 && $price <= 20) {
                $cheapCategory = Category::firstOrCreate([
                    "name" => "Cheap"
                ]);
                $cheapCategory->activities()->sync([$activity->id], false);
            }

            Log::info('created activity ' . $activity->title . ' ' . $activity->price);

            $start = Carbon::parse($deal->startAt);
            $end = Carbon::parse($deal->endAt);
            $timetable = Timetable::firstOrCreate([
                "activity_id" => $activity->id,
                "start_time" => $start,
                "end_time" => $end
            ]);

            // do category
            return $activity->id;
        }, $groupon->deals);
        $category->activities()->sync($activityIds, false);
    }
}