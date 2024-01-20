<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Http\Controllers\HoInventoryReport;
use App\Http\Controllers\HR\PayrollCalculateController as PayrollCalculateController;
use App\Http\Controllers\HR\ScheduleLocalController as Biometrics;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\Auto_Backup_Database'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // for Health and safety Utilization report
        // $schedule->call(HoInventoryReport::monthlyUtilBalance())->monthly();
        $schedule->call(PayrollCalculateController::updateTimecards())->cron('* * * * *');

        // for local server
        $schedule->call(Biometrics::getBiometric('dev'))->name('biometric_update')->everyTwoMinutes()->withoutOverlapping();
        $schedule->call(Biometrics::getAttendance())->name('fetch_attendance')->everyMinute()->withoutOverlapping();
        $schedule->call(Biometrics::sendAttendance('dev'))->name('send_attendance')->everyMinute()->withoutOverlapping();
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
