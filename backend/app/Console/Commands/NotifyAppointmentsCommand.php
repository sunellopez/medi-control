<?php

namespace App\Console\Commands;

use App\Jobs\SendAppointmentReminderJob;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyAppointmentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS notifications for appointments scheduled for tomorrow';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrowStart = Carbon::tomorrow()->startOfDay();
        $tomorrowEnd = Carbon::tomorrow()->endOfDay();

        $appointments = Appointment::with('patient')
            ->whereBetween('appointment_date', [$tomorrowStart, $tomorrowEnd])
            ->where('status', '!=', 'cancelled')
            ->get();

        $count = $appointments->count();
        $this->info("Found {$count} appointments for tomorrow.");

        foreach ($appointments as $appointment) {
            SendAppointmentReminderJob::dispatch($appointment);
        }

        $this->info('Reminders dispatched successfully.');
    }
}
