<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cards extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_type',
        'masked_card_number',
        'card_number',
        'card_holder_name',
        'card_expiration_date',
        'card_cvv',
        'is_otp_disabled',
        'access_key',
    ];
}
