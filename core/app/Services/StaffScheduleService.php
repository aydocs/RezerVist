<?php

namespace App\Services;

use App\Models\Business;
use App\Models\Staff;
use App\Models\StaffSchedule;
use Carbon\Carbon;

class StaffScheduleService
{
    /**
     * Check if a staff member is on shift.
     */
    public function isStaffOnShift(Staff $staff, Carbon $dateTime): bool
    {
        return StaffSchedule::where('staff_id', $staff->id)
            ->where('day_of_week', $dateTime->dayOfWeek)
            ->where('shift_start', '<=', $dateTime->format('H:i'))
            ->where('shift_end', '>=', $dateTime->format('H:i'))
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Get available staff for a specific time and location.
     */
    public function getAvailableStaff(Business $business, Carbon $dateTime, $locationId = null)
    {
        $query = Staff::where('business_id', $business->id)
            ->where('is_active', true)
            ->whereHas('schedules', function ($q) use ($dateTime) {
                $q->where('day_of_week', $dateTime->dayOfWeek)
                    ->where('shift_start', '<=', $dateTime->format('H:i'))
                    ->where('shift_end', '>=', $dateTime->format('H:i'))
                    ->where('status', 'active');
            });

        if ($locationId) {
            $query->where('location_id', $locationId);
        }

        return $query->get();
    }

    /**
     * Generate a default schedule for staff (stub for AI logic).
     */
    public function autoGenerateSchedule(Staff $staff)
    {
        // Example: 9-5 for weekdays
        foreach ([1, 2, 3, 4, 5] as $day) {
            StaffSchedule::updateOrCreate(
                ['staff_id' => $staff->id, 'day_of_week' => $day],
                [
                    'business_id' => $staff->business_id,
                    'shift_start' => '09:00',
                    'shift_end' => '17:00',
                    'status' => 'active'
                ]
            );
        }
    }
}
