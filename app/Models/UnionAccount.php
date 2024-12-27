<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnionAccount extends Authenticatable
{
    protected $fillable = [
        'name',
        'username',
        'password',
        'email',
        'phone',
        'account_type'
    ];

    protected $hidden = [
        'password'
    ];

    public function isMedical(): bool
    {
        if (isset($this->account_type)) {
            return $this->account_type === 'medical_center';
        }

        return false;
    }

    public function unionMedicalCenterList(): HasMany
    {
        return $this->hasMany(UnionMedicalCenterList::class, 'union_id', 'id');
    }

    public function unionUserList(): HasMany
    {
        return $this->hasMany(UnionUserList::class, 'union_id', 'id');
    }

    public function unionNotification()
    {
        $medical_centers = $this->hasMany(UnionMedicalCenterList::class, 'union_id', 'id');
        $medical_center_ids = $medical_centers->pluck('medical_center_id')->toArray();

        return Notification::select('notifications.*', 'applications.id', 'applications.center_name', 'medical_centers.id', 'medical_centers.username')
            ->join('applications', 'applications.id', '=', 'notifications.application_id')
            ->join('medical_centers', 'medical_centers.username', '=', 'applications.center_name')
            ->whereIn('medical_centers.id', $medical_center_ids)
            ->whereDate('notifications.created_at', '>=', now()->subDays(2))
            ->latest()
            ->get();
    }
}
