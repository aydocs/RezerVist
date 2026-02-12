<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessRecurringReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:process-recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process and create next instances of recurring reservations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing recurring reservations...');

        // Find reservations that are recurring and have reached their "generate next" window
        // For simplicity, we check for recurring reservations whose last instance is coming up soon
        $recurringReservations = Reservation::where('is_recurring', true)
            ->where(function ($query) {
                $query->whereNull('recurrence_end_date')
                    ->orWhere('recurrence_end_date', '>', now());
            })
            ->whereNotExists(function ($query) {
                // Only process the latest instance of each recurring chain
                $query->select(\Illuminate\Support\Facades\DB::raw(1))
                    ->from('reservations as next_instances')
                    ->whereRaw('next_instances.parent_reservation_id = reservations.id');
            })
            ->get();

        foreach ($recurringReservations as $reservation) {
            $this->createNextInstance($reservation);
        }

        $this->info('Recurring reservations processed successfully.');
    }

    /**
     * Create the next instance based on the pattern
     */
    protected function createNextInstance(Reservation $reservation)
    {
        $nextStart = null;
        $nextEnd = null;

        $currentStart = Carbon::parse($reservation->start_time);
        $currentEnd = Carbon::parse($reservation->end_time);

        switch ($reservation->recurrence_pattern) {
            case 'daily':
                $nextStart = $currentStart->addDay();
                $nextEnd = $currentEnd->addDay();
                break;
            case 'weekly':
                $nextStart = $currentStart->addWeek();
                $nextEnd = $currentEnd->addWeek();
                break;
            case 'monthly':
                $nextStart = $currentStart->addMonth();
                $nextEnd = $currentEnd->addMonth();
                break;
        }

        if (! $nextStart) {
            return;
        }

        // Don't create if it's past the end date
        if ($reservation->recurrence_end_date && $nextStart->gt($reservation->recurrence_end_date)) {
            return;
        }

        // Check if next instance already exists (safety check)
        $exists = Reservation::where('business_id', $reservation->business_id)
            ->where('user_id', $reservation->user_id)
            ->where('start_time', $nextStart)
            ->exists();

        if ($exists) {
            $this->warn("Reservation instance already exists for {$reservation->id} at {$nextStart}");

            return;
        }

        // Create the next instance
        $newReservation = $reservation->replicate(['id', 'created_at', 'updated_at']);
        $newReservation->start_time = $nextStart;
        $newReservation->end_time = $nextEnd;
        $newReservation->parent_reservation_id = $reservation->id;
        $newReservation->status = 'confirmed'; // Auto-confirm recurring? Or keep as pending?
        $newReservation->save();

        // Copy menu relations
        foreach ($reservation->menus as $menu) {
            $newReservation->menus()->attach($menu->id, ['price' => $menu->pivot->price]);
        }

        $this->line("Created next instance for reservation {$reservation->id} on {$nextStart->toDateString()}");
    }
}
