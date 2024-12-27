<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlipMedicalCenterRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_center_slug',
        'rate',
        'discount',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'discount' => 'integer',
    ];
}
