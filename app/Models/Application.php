<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Application extends Model
{
    protected $fillable = [
        'medical_type',
        'user_id',
        'ref_ledger',
        'passport_number',
        'gender',
        'traveling_to',
        'marital_status',
        'center_name',
        'surname',
        'given_name',
        'father_name',
        'mother_name',
        'religion',
        'pp_issue_place',
        'profession',
        'nationality',
        'date_of_birth',
        'contact_no',
        'nid_no',
        'passport_issue_date',
        'passport_expiry_date',
        'ref_no',
        'health_status',
        'health_status_details',
        'serial_number',
        'ems_number'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'passport_issue_date' => 'date',
        'passport_expiry_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function medicalCenter(): BelongsTo
    {
        return $this->belongsTo(MedicalCenter::class, 'center_name', 'username');
    }

    public function allocatedMedicalCenter(): HasOne
    {
        return $this->hasOne(AllocateMedicalCenter::class, 'application_id', 'id');
    }
}
