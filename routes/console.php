<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Status monitoring schedule - runs every 5 minutes
Schedule::command('status:monitor')->everyFiveMinutes();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the monitoring command to run every 5 minutes
Schedule::command('status:monitor')->everyFiveMinutes();
