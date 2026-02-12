<?php

use App\Models\Business;
use App\Models\BusinessHour;
use Carbon\Carbon;

// Disable output buffering
ob_end_clean();

$id = 6;
$date = '2026-01-16';

echo "Debugging Logic for Date: $date\n";

$business = Business::find($id);
$carbonDate = Carbon::parse($date);
$dayOfWeek = $carbonDate->dayOfWeek;
echo "DayOfWeek: $dayOfWeek (0=Sun, 5=Fri)\n";

$hours = BusinessHour::where('business_id', $id)
    ->where('day_of_week', $dayOfWeek)
    ->whereNull('special_date')
    ->first();

if (! $hours) {
    exit("No hours found!\n");
}

echo 'DB Open: '.$hours->open_time."\n";
echo 'DB Close: '.$hours->close_time."\n";

$openTimeStr = $hours->open_time instanceof Carbon ? $hours->open_time->format('H:i') : Carbon::parse($hours->open_time)->format('H:i');
$closeTimeStr = $hours->close_time instanceof Carbon ? $hours->close_time->format('H:i') : Carbon::parse($hours->close_time)->format('H:i');

echo "Parsed Open: $openTimeStr\n";
echo "Parsed Close: $closeTimeStr\n";

$start = Carbon::parse($date.' '.$openTimeStr);
$end = Carbon::parse($date.' '.$closeTimeStr);

if ($end->lessThan($start)) {
    $end->addDay();
    echo 'End added day: '.$end->toDateTimeString()."\n";
} else {
    echo 'End is same day: '.$end->toDateTimeString()."\n";
}

$now = Carbon::now();
echo 'NOW (Server Time): '.$now->toDateTimeString()."\n";
echo 'Is Today? '.($carbonDate->isToday() ? 'YES' : 'NO')."\n";

$current = $start->copy();
echo "--- Loop Start ---\n";
while ($current->lt($end)) {
    $slotTime = $current->format('H:i');
    $isPast = false;

    if ($carbonDate->isToday()) {
        $threshold = $now->copy()->addMinutes(15);
        if (! $current->gt($threshold)) {
            $isPast = true;
            echo "Slot $slotTime is PAST (Threshold: ".$threshold->toDateTimeString().")\n";
        } else {
            echo "Slot $slotTime is VALID\n";
        }
    } else {
        echo "Slot $slotTime is VALID (Future Date)\n";
    }

    $current->addHour();
}
