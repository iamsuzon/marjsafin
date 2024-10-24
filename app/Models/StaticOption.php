<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    public static function getOption($option_key)
    {
        $option = StaticOption::where('key', $option_key)->first();
        if ($option) {
            return $option->value;
        }

        return null;
    }

    public static function setOption($key, $value): void
    {
        $option = StaticOption::where('key', $key)->first();
        if ($option) {
            $option->value = $value;
            $option->save();
        } else {
            StaticOption::create([
                'key' => $key,
                'value' => $value,
            ]);
        }
    }
}
