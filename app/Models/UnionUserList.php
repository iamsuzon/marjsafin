<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnionUserList extends Model
{
    use HasFactory;

    protected $fillable = [
        'union_id',
        'user_id'
    ];
}
