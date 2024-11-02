<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'application_id',
        'amount',
        'payment_type',
        'payment_method',
        'reference_no',
        'deposit_date',
        'remarks',
        'deposit_slip',
        'status'
    ];

    protected $casts = [
        'application_id', 'integer',
        'user_id' => 'integer',
        'amount' => 'double',
        'deposit_date' => 'date',
        'status' => 'string'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
