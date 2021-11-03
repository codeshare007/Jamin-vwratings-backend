<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static firstOrCreate(array $array)
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
