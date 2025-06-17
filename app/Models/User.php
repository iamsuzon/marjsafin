<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'refer_by',
        'balance',
        'slip_balance',
        'has_medical_permission',
        'has_slip_permission',
        'has_link_permission'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'balance' => 'integer',
        'slip_balance' => 'integer',
        'has_medical_permission' => 'boolean',
        'has_slip_permission' => 'boolean',
        'has_link_permission' => 'boolean'
    ];

    public function banned(): HasOne
    {
        return $this->hasOne(BannedUser::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'user_id', 'id');
    }

    public function slip()
    {
        return $this->hasMany(Slip::class, 'user_id', 'id');
    }

    public function appointmentBooking(): HasMany
    {
        return $this->hasMany(AppointmentBooking::class, 'user_id', 'id');
    }

    public function card(): HasOne
    {
        return $this->hasOne(Cards::class, 'user_id', 'id')->where('user_type', 1);
    }

    public function slipLogs(): HasMany
    {
        return $this->hasMany(CustomerSlipLog::class , 'user_id', 'id');
    }
}
