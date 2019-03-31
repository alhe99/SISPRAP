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
        Commands\CleanBackups::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*  ->weekdays()->between('16:00', '17:00')->appendOutputTo(storage_path('logs/scheduler.log')); */
        /*         $schedule->command("clean:backup")->weekdays()->between('16:30:01','16:30:59');
        $schedule->command("backup:run --only-db")->weekdays()->between('17:00:01', '17:00:59'); */
        $schedule->command("backup:clean");
        $schedule->command("backup:run --only-db");
        
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
