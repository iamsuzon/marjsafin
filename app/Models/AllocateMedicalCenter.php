<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllocateMedicalCenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_center_id',
        'application_id',
        'allocated_medical_center'
    ];
}
