<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static firstOrCreate(array $array)
 */
class UsersFavoriteAvis extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'avis_id'];

    protected $with = ['avi'];

    public $timestamps = false;

    /**
     * @return HasOne
     */
    public function avi(): HasOne
    {
        return $this->hasOne(Avi::class, 'id', 'avis_id');
    }
}
