<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail($id)
 * @method static where(string $string, string $string1, $key)
 */
class Settings extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * @param $key
     * @return mixed
     */
    public static function getSetting($key)
    {
        return Settings::where('key', '=', $key)->first()->value;
    }
}
