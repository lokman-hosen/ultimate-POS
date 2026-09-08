<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use Illuminate\Support\Facades\Schedule;

// Send pending finalized invoices at 2:00 AM daily (low traffic)
Schedule::command('verifactu:send --batch=100')
    ->dailyAt('02:00')
    ->withoutOverlapping();

// Check status every 30 minutes
Schedule::command('verifactu:check-status --batch=50')
    ->everyThirtyMinutes()
    ->withoutOverlapping();

// Retry failed submissions every hour
Schedule::command('verifactu:send --batch=50 --force')
    ->hourly()
    ->withoutOverlapping();
