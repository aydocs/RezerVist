<?php

namespace App\Models;

use App\Traits\HasBusinessScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffSchedule extends Model
{
    use HasFactory, HasBusinessScope;

    protected $fillable = [
        'business_id',
        'staff_id',
        'day_of_week',
        'shift_start',
        'shift_end',
        'status',
        'notes',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
