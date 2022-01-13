<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail($id)
 * @method static paginate(int $int)
 * @method static create(array $array)
 */
class Notifications extends Model
{
    use HasFactory;

    protected $fillable = ['content'];
}
