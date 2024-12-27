<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['message', 'link', 'read_at', 'user_id', 'application_id', 'extra'];

    protected $casts = [
        'read_at' => 'datetime',
        'extra' => 'string'
    ];
}
