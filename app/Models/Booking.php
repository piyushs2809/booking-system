<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'booking_date',
        'booking_type',
        'booking_slot',
        'booking_from_time',
        'booking_to_time'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'booking_from_time' => 'datetime:H:i',
        'booking_to_time' => 'datetime:H:i'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Optimized overlap detection method
    public static function hasOverlap($date, $type, $slot = null, $fromTime = null, $toTime = null, $excludeId = null)
    {
        $query = self::where('booking_date', $date);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        // Check for full day conflicts
        if ($type === 'full_day') {
            return $query->exists(); // Full day conflicts with any existing booking
        }

        // If there's already a full day booking
        if ($query->where('booking_type', 'full_day')->exists()) {
            return true;
        }

        if ($type === 'half_day') {
            // Disallow if any half_day booking exists already on the same date
            $halfDayExists = self::where('booking_date', $date)
                                ->where('booking_type', 'half_day')
                                ->where('booking_slot', $slot)
                                ->exists();
            
            if ($halfDayExists) {
                return true; // Conflict found
            }
        
            // Check for custom type overlapping with the selected half-day slot
            $timeRange = self::getHalfDayTimeRange($slot);
            return self::where('booking_date', $date)
                        ->where('booking_type', 'custom')
                        ->where(function ($q) use ($timeRange) {
                            $q->whereBetween('booking_from_time', $timeRange)
                              ->orWhereBetween('booking_to_time', $timeRange)
                              ->orWhere(function ($inner) use ($timeRange) {
                                  $inner->where('booking_from_time', '<=', $timeRange[0])
                                        ->where('booking_to_time', '>=', $timeRange[1]);
                              });
                        })->exists();
        }

        if ($type === 'custom') {
            // Check against half day bookings
            $conflictingSlots = [];
            
            if (self::timeOverlapsWithSlot($fromTime, $toTime, 'first_half')) {
                $conflictingSlots[] = 'first_half';
            }
            if (self::timeOverlapsWithSlot($fromTime, $toTime, 'second_half')) {
                $conflictingSlots[] = 'second_half';
            }

            if (!empty($conflictingSlots)) {
                if ($query->where('booking_type', 'half_day')
                         ->whereIn('booking_slot', $conflictingSlots)
                         ->exists()) {
                    return true;
                }
            }

            // Check against other custom bookings
            return $query->where('booking_type', 'custom')
                        ->where(function ($q) use ($fromTime, $toTime) {
                            $q->where(function ($inner) use ($fromTime, $toTime) {
                                // Overlap conditions
                                $inner->where('booking_from_time', '<', $toTime)
                                      ->where('booking_to_time', '>', $fromTime);
                            });
                        })->exists();
        }

        return false;
    }

    private static function getHalfDayTimeRange($slot)
    {
        return $slot === 'first_half' 
            ? ['09:00:00', '13:00:00'] 
            : ['13:00:00', '18:00:00'];
    }

    private static function timeOverlapsWithSlot($fromTime, $toTime, $slot)
    {
        $slotRange = self::getHalfDayTimeRange($slot);
        return $fromTime < $slotRange[1] && $toTime > $slotRange[0];
    }
}
