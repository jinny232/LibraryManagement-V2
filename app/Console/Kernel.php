<?php

namespace App\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
    // app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    // Run the command daily at midnight.
    $schedule->command('borrowings:update-status')->daily();

    // Or, run it hourly.
    // $schedule->command('borrowings:update-status')->hourly();
}
}
