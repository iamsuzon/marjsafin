<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerSlipLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'slip_amount',
        'amount',
        'note'
    ];

    protected $casts = [
        'slip_amount' => 'integer',
    ];

    public function getSlipAmountAttribute($value): string
    {
        return (int) $value;
    }
    public function getAmountAttribute($value): string
    {
        return (int) $value;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
