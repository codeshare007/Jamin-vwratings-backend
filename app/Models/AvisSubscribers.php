<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static firstOrCreate(array $array)
 */
class AvisSubscribers extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
        'avis_id',
        'status'
    ];
}
