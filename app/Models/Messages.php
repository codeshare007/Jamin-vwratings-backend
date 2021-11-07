<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static firstOrCreate(array $array)
 * @method static paginate(int $int)
 * @method static orderBy(mixed $get, mixed $get1)
 * @method static whereIn(string $string, false|string[] $explode)
 * @method static find($id)
 */
class Messages extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'content'
    ];
}
