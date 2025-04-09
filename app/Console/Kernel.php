<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\CheckDueDates::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // You can schedule commands here if needed
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
