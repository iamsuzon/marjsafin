<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentBookingLink extends Model
{
    protected $fillable = [
        'appointment_booking_id',
        'url',
        'type',
        'status',
    ];

    protected $casts = [
        'appointment_booking_id' => 'integer',
        'url' => 'string',
        'type' => 'string',
        'status' => 'string',
    ];

    public function appointmentBooking(): BelongsTo
    {
        return $this->belongsTo(AppointmentBooking::class, 'appointment_booking_id');
    }
}
