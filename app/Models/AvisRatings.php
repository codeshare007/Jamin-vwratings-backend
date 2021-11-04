<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static firstOrCreate(array $array)
 * @method static updateOrCreate(array $array)
 */
class AvisRatings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'avis_id',
        'rating'
    ];
}
