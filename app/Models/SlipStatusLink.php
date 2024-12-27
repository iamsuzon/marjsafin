<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlipStatusLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'slip_id',
        'slip_status',
        'link'
    ];

    public function slip()
    {
        return $this->belongsTo(Slip::class);
    }
}
