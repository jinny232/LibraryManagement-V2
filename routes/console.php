<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\UpdateOverdueStatus;
use App\Console\Commands\DeleteExpiredMembers;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule your commands directly here.
Schedule::command(UpdateOverdueStatus::class)->everyMinute();
Schedule::command(DeleteExpiredMembers::class)->everyMinute();
