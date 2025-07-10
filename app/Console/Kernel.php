<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\CheckTodoReminders;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        CheckTodoReminders::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Check for reminders every minute
        $schedule->command('todos:check-reminders')->everyMinute();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
