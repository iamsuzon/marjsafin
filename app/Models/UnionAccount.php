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

    public function unionMedicalCenterList(): HasMany
    {
        return $this->hasMany(UnionMedicalCenterList::class, 'union_id', 'id');
    }

    public function unionUserList(): HasMany
    {
        return $this->hasMany(UnionUserList::class, 'union_id', 'id');
    }
}
