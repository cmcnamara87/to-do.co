<?php

namespace App\Jobs;

use App\Activity;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateActivity extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $title;
    protected $description;

    function __construct($title, $description)
    {
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return Activity::create([
            "title" => $this->title,
            "description" => $this->description
        ]);
    }
}
