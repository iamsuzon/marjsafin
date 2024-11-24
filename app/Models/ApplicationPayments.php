<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationPayments extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'center_amount',
        'admin_amount',
        'discount_amount'
    ];

    protected $casts = [
        'application_id' => 'integer',
        'center_amount' => 'double',
        'admin_amount' => 'double',
        'discount_amount' => 'double'
    ];
}
