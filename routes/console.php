<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Status monitoring schedule - runs every 5 minutes
Schedule::command('status:monitor')->everyFiveMinutes();

// Incident cleanup - runs daily at 2 AM (configurable retention period)
Schedule::command('incidents:cleanup')->dailyAt('02:00');

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
