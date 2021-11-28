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
        'id',
        'user_id',
        'avis_id',
        'rating'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function avi()
    {
        return $this->hasOne(Avi::class, 'id', 'avis_id');
    }
}
