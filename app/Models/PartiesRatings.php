<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartiesRatings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'party_id',
        'rating'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function party()
    {
        return $this->hasOne(Parties::class, 'id', 'party_id');
    }
}
