<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnionMedicalCenterList extends Model
{
    use HasFactory;

    protected $fillable = [
        'union_id',
        'medical_center_id',
    ];
}
