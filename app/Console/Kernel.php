<?php

namespace App\Console;

use App\Jobs\TeamSpeakCheckNames;
use App\Jobs\TeamSpeakKickMusicBotsAndInactive;
use App\Jobs\TeamSpeakOldActivationNotification;
use App\Jobs\TeamSpeakSyncGroups;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
        // $schedule->job(new TeamSpeakKickMusicBotsAndInactive)->cron('* * * * *');
        $schedule->job(new TeamSpeakCheckNames)->cron('* * * * *');
        $schedule->job(new TeamSpeakOldActivationNotification)->cron('*/2 * * * *');
        $schedule->job(new TeamSpeakSyncGroups)->cron('*/5 * * * *');
    }

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
}
