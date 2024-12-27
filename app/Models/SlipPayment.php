<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlipPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'slip_id',
        'slip_rate',
        'discount',
        'payment_status'
    ];

    protected $casts = [
        'slip_rate' => 'double',
        'discount' => 'double'
    ];
}
