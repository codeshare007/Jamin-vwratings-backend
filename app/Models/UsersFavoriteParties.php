<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class UsersFavoriteParties extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = ['user_id', 'party_id'];

    protected $with = ['party'];

    public $timestamps = false;

    /**
     * @return HasOne
     */
    public function party(): HasOne
    {
        return $this->hasOne(Parties::class, 'id', 'party_id');
    }
}
