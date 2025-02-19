<?php
declare(strict_types=1);
namespace LaravelCompany\Mail\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use LaravelCompany\Mail\Console\Commands\CampaignDispatchCommand;
use LaravelCompany\Mail\Console\Commands\CreateTodayCampaign;
use LaravelCompany\Mail\Console\Commands\ValidateSubscribers;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided Laravel Mail Application.
     *
     * @var array
     */
    protected $commands = [
        CampaignDispatchCommand::class,
        ValidateSubscribers::class,
        CreateTodayCampaign::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command("campaigns:dispatch")->everyMinute()->withoutOverlapping();
        $schedule->command("app:validate-subscribers")->everyMinute()->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
