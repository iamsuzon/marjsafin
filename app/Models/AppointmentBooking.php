<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentBooking extends Model
{
    protected $fillable = [
        'status',
        'user_id',
        'type',
        'reference',
        'center_name',
        'country',
        'city',
        'country_traveling_to',
        'first_name',
        'last_name',
        'dob',
        'nationality',
        'gender',
        'marital_status',
        'passport_number',
        'passport_issue_date',
        'passport_issue_place',
        'passport_expiry_date',
        'visa_type',
        'email',
        'phone_number',
        'nid_number',
        'applied_position',
        'note'
    ];

    protected $casts = [
        'status' => 'boolean',
        'user_id' => 'integer',
        'type' => 'string',
        'reference' => 'string',
        'city' => 'integer',
        'dob' => 'date',
        'passport_issue_date' => 'datetime',
        'passport_expiry_date' => 'datetime',
        'applied_position' => 'integer',
    ];

    protected function getPassportIssueDateAttribute($value): string
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    protected function getPassportExpiryDateAttribute($value): string
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(AppointmentBookingLink::class, 'appointment_booking_id');
    }
}
