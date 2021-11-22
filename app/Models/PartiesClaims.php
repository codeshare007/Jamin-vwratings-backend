<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartiesClaims extends Model
{
    use HasFactory;

    protected $with = ['party'];

    protected $fillable = ['user_id', 'party_id', 'claimed_until'];

    public $timestamps = false;

    public function party()
    {
        return $this->hasOne(Parties::class, 'id', 'party_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
