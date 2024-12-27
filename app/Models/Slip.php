<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Slip extends Model
{
    use HasFactory;

    protected $fillable = [
        'pdf_code',
        'slip_type',
        'user_id',
        'ref_ledger',
        'passport_number',
        'gender',
        'city_id',
        'center_slug',
        'marital_status',
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
        'ref_no'
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

    public function slipPayment(): HasOne
    {
        return $this->hasOne(SlipPayment::class);
    }

    public function paymentLog(): HasOne
    {
        return $this->hasOne(PaymentLog::class, 'application_id', 'id')
            ->where('score_type', 'slip');
    }

    public function slipStatusLink(): HasOne
    {
        return $this->hasOne(SlipStatusLink::class);
    }

    public function notification()
    {
        return $this->hasOne(Notification::class, 'application_id', 'id')
            ->where('extra->type', 'slip');
    }
}
