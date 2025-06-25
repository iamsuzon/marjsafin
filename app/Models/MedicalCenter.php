<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MedicalCenter extends Authenticatable
{
    protected $fillable = [
        'name',
        'username',
        'email',
        'email_verified_at',
        'password',
        'password_changed_at',
        'address',
        'note'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_changed_at' => 'datetime',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'center_name', 'username');
    }

    public function allocatedMedicalCenter(): HasOne
    {
        return $this->hasOne(AllocateMedicalCenter::class, 'medical_center_id', 'id');
    }
}
