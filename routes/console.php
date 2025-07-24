<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Smart scheduler runs every minute to process due checks
Schedule::command('scheduler:smart run')
    ->everyMinute()
    ->withoutOverlapping(5)
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/scheduler.log'));

// Initialize smart scheduler for new services every 5 minutes
Schedule::command('scheduler:smart init')
    ->everyFiveMinutes()
    ->withoutOverlapping(2)
    ->runInBackground();

// Incident cleanup - runs daily at 2 AM
Schedule::command('incidents:cleanup')
    ->dailyAt('02:00')
    ->runInBackground();

// Health check for monitoring (optional - logs system health)
Schedule::call(function () {
    $response = app(\App\Http\Controllers\HealthController::class)->check();
    if ($response->getStatusCode() !== 200) {
        \Log::warning('Health check failed', $response->getData(true));
    }
})->everyTenMinutes();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
