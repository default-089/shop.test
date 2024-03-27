<?php

namespace App\Console;

use App\Jobs\Mailing\DiscountAfterRegisterJob;
use App\Jobs\Mailing\LeaveFeedbackAfterOrderJob;
use App\Jobs\Mailing\SendingTracksJob;
use App\Jobs\OneC\UpdateOfflineOrdersJob;
use App\Jobs\Payment\SendInstallmentNoticeJob;
use App\Jobs\SxGeoUpdateJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\App;

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
     */
    protected function schedule(Schedule $schedule): void
    {
        if (!App::environment('production')) {
            return;
        }
        // $schedule->command('inspire')->hourly();

        $schedule->job(new SxGeoUpdateJob)->dailyAt('03:07');
        $schedule->job(new DiscountAfterRegisterJob)->dailyAt('09:00');
        $schedule->job(new SendInstallmentNoticeJob)->dailyAt('09:05');
        $schedule->job(new LeaveFeedbackAfterOrderJob)->dailyAt('09:15');
        $schedule->job(new SendingTracksJob)->dailyAt('10:15');
        $schedule->job(new UpdateOfflineOrdersJob)->withoutOverlapping()->everyTenMinutes();

        $schedule->command('rating:update')->withoutOverlapping()->cron('15 5,11,17,23 * * *');
        $schedule->command('inventory:update')->withoutOverlapping()->everyFifteenMinutes()->sentryMonitor();

        $schedule->command('backup:run')->dailyAt('01:00');
        $schedule->command('backup:media')->weeklyOn(Schedule::MONDAY, '03:00');
        $schedule->command('backup:clean')->dailyAt('06:00');
        $schedule->command('backup:monitor')->dailyAt('06:30');

        $schedule->command('feed:generate')->everySixHours();
        $schedule->command('generate:sitemap')->dailyAt('00:30');

        $schedule->command('erip:update-statuses')->everyTenMinutes();
        $schedule->command('belpost:cod-parse-from-email')->hourly()->between('8:00', '18:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
