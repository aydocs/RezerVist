<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationReminder;
use Carbon\Carbon;

class SendReservationReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders for reservations upcoming in 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting reservation reminders check...');

        $startWindow = Carbon::now()->addHours(23);
        $endWindow = Carbon::now()->addHours(25);

        // Include both 'approved' and 'confirmed' (paid) reservations
        $reservations = Reservation::whereIn('status', ['approved', 'confirmed'])
            ->whereBetween('start_time', [$startWindow, $endWindow])
            ->whereNull('reminded_at')
            ->with(['user', 'business'])
            ->get();

        $this->info("Found {$reservations->count()} reservations to remind.");

        foreach ($reservations as $reservation) {
            try {
                // Use Notification instead of direct Mail for multi-channel support
                $reservation->user->notify(new \App\Notifications\ReservationReminderNotification($reservation));
                
                $reservation->update(['reminded_at' => now()]);
                
                $this->info("Reminder sent for Reservation #{$reservation->id}");
            } catch (\Exception $e) {
                $this->error("Failed to send reminder for Reservation #{$reservation->id}: " . $e->getMessage());
                \Log::error("Reminder failed for #{$reservation->id}: " . $e->getMessage());
            }
        }

        $this->info('Reservation reminders check completed.');
        return Command::SUCCESS;
    }
}
