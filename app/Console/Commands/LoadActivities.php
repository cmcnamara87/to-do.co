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
        // get the activites from bcc

        // south bank feed
        $url = "http://www.trumba.com/calendars/south-bank.rss?filterview=south+bank&filter4=_464155_&filterfield4=22542";
        $xml = simplexml_load_file($url);
        foreach($xml->channel->item as $item) {

            //Use that namespace
            $namespaces = $item->getNameSpaces(true);
            //Now we don't have the URL hard-coded
            $trumba = $item->children($namespaces['x-trumba']);
            $xcal = $item->children($namespaces['xCal']);
//            echo $trumba->localstart;
//            echo $trumba->creator;

            /*

            <title>Ginger Sports soccer workshop - Little Days Out</title>
<description>
Thursday, February 18, 2016, 9am&nbsp;&ndash;&nbsp;1pm <br/><br/><img src="http://www.trumba.com/i/DgD2YwNlDZs5gTkIbu7EElPz.jpg" width="132" height="116" /><br/><br/>At Ginger Sports it&#8217;s not all about bending it like Beckham. It&#8217;s also about providing a fun, inclusive, play-based learning environment that lays the foundation for a happy and healthy life for our precious kids.<br /> <br /> Let them practise their coordination and teamwork skills with a game of soccer while making friends.<br /> <br /> Young toddlers to school aged children will enjoy this fun day out!<br /> <br /> Location:&#160;Little Stanley Street Lawns North <br/><br/><b>Cost</b>:&nbsp;Free <br/><b>Bookings</b>:&nbsp;Ginger Sports is part of South Bank&#8217;s Little Days Out program. There&#8217;s no need to book or register to take part &#8211; simply head along and get ready to have fun! <br/><b>Venue</b>:&nbsp;<a href="javascript:Nav(&#39;objectid=464155&amp;objecttype=19243&amp;key=9d2a53b72ad82c6e075179c292616902&amp;view=object&amp;-childview=&amp;-index=&#39;,&#39;detailBase&#39;)">South Bank Parklands</a> <br/><b>Venue address</b>:&nbsp;<a href="http://maps.google.com/?q=-27.478191,153.022929(South+Bank+Parklands)" target="_blank">South Bank Parklands, Cnr Ernest Street, Stanley Street and Grey Street, South Brisbane, Australia</a> <br/><b>Schedule</b>:&nbsp;You don&#8217;t need to be there for the whole time. Instead, you can come and go as you please between 9am and 1pm. If you have any questions about the event, email <a href="mailto:vicsouthbank@brisbanemarketing.com.au" target="_blank">vicsouthbank@brisbanemarketing.com.au</a>., In the event of bad weather, please check the South Bank Facebook page to see if this event is proceeding. <br/><b>More info</b>:&nbsp;<a href="http://www.visitbrisbane.com.au/south-bank/whats-on/free/ginger-sports-soccer-at-little-days-out" target="_blank" title="http://www.visitbrisbane.com.au/south-bank/whats-on/free/ginger-sports-soccer-at-little-days-out">www.visitbrisbane.com.au&#8230;</a> <br/><br/>
</description>
<link>
http://www.brisbane.qld.gov.au/whats-brisbane/events-council-venues/parks-gardens-events/south-bank-parklands-events?trumbaEmbed=view%3devent%26eventid%3d118060286
</link>
<x-trumba:ealink>
https://eventactions.com/eventactions/south-bank#/actions/g1nfe6z3ndzukrfm4kcbtbf006
</x-trumba:ealink>
<category>2016/02/18 (Thu)</category>
<guid isPermaLink="false">http://uid.trumba.com/event/118060286</guid>
<xCal:summary>Ginger Sports soccer workshop - Little Days Out</xCal:summary>
<xCal:location>South Bank Parklands</xCal:location>
<xCal:dtstart>2016-02-17T23:00:00Z</xCal:dtstart>
<x-trumba:localstart tzAbbr="EAST" tzCode="260">2016-02-18T09:00:00</x-trumba:localstart>
<x-trumba:formatteddatetime>Thursday, February 18, 2016, 9am - 1pm</x-trumba:formatteddatetime>
<xCal:dtend>2016-02-18T03:00:00Z</xCal:dtend>
<x-trumba:localend tzAbbr="EAST" tzCode="260">2016-02-18T13:00:00</x-trumba:localend>
<x-microsoft:cdo-alldayevent>false</x-microsoft:cdo-alldayevent>
<xCal:description>
At Ginger Sports it’s not all about bending it like Beckham. It’s also about providing a fun, inclusive, play-based learning environment that lays the foundation for a happy and healthy life for our precious kids. Let them practise their coordination and teamwork skills with a game of soccer while making friends. Young toddlers to school aged children will enjoy this fun day out! Location:&nbsp;Little Stanley Street Lawns North
</xCal:description>
<xCal:uid>http://uid.trumba.com/event/118060286</xCal:uid>
<x-trumba:customfield name="Event Type" id="21" type="number">Brisbane City Council</x-trumba:customfield>
<x-trumba:customfield name="Cost" id="22177" type="text">Free</x-trumba:customfield>
<x-trumba:customfield name="Event image" id="40" type="uri" imageWidth="200" imageHeight="177">
http://www.trumba.com/i/DgD2YwNlDZs5gTkIbu7EElPz.jpg
</x-trumba:customfield>
<x-trumba:customfield name="Bookings" id="22732" type="text">
Ginger Sports is part of South Bank’s Little Days Out program. There’s no need to book or register to take part – simply head along and get ready to have fun!
</x-trumba:customfield>
<x-trumba:customfield name="Venue" id="22542" type="text">South Bank Parklands</x-trumba:customfield>
<x-trumba:customfield name="Venue address" id="22505" type="text">
South Bank Parklands, Cnr Ernest Street, Stanley Street and Grey Street, South Brisbane, Australia
</x-trumba:customfield>
<x-trumba:customfield name="Schedule" id="31285" type="text">
You don’t need to be there for the whole time. Instead, you can come and go as you please between 9am and 1pm. If you have any questions about the event, email vicsouthbank@brisbanemarketing.com.au. In the event of bad weather, please check the South Bank Facebook page to see if this event is proceeding.
</x-trumba:customfield>
<x-trumba:categorycalendar>South Bank Parklands</x-trumba:categorycalendar>
<x-trumba:weblink>
http://www.visitbrisbane.com.au/south-bank/whats-on/free/ginger-sports-soccer-at-little-days-out
</x-trumba:weblink>

             */
            $this->info($item->title);

            $image_url = '';
            foreach($trumba->customfield as $customField) {
                if($customField->attributes()->name == "Event image") {
                    $image_url = (string)$customField;
                }
            }

            $title = (string)$item->title;
            $description = (string)$xcal->description;

            $activity = Activity::where('title', $title)->first();
            if(!$activity) {
                $activity = Activity::create([
                    "title" => $title,
                    "description" => $description,
                    "weblink" => (string)$trumba->weblink,
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
}
