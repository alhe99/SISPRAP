<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        setlocale(LC_ALL,"es_ES");
        $command = "db:backup --database=mysql --destination=local --destinationPath=dbsisprap_".strftime("%A_%d_%B_%Y").".sql --compression=null";
        $schedule->command($command)->everyMinute();
        /* appendOutputTo(storage_path('logs/scheduler.log'))->runInBackground() */
    }

    /**
     * Register the commands for the application.
     *
     * @return void2
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
