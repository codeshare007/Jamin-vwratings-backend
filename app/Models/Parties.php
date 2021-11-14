<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parties extends Model
{
    use HasFactory;

    public function getUserRatingAttribute()
    {
        if (auth()->check()) {
            if ($rating = $this->ratings()->where([
                'party_id' => $this->attributes['id'],
                'user_id' => auth()->user()->getAuthIdentifier()
            ])->first()) {
                return $rating->rating;
            }
        }

        return null;
    }

    public function getAverageRatingAttribute()
    {
        return $this->ratings->average('rating');
    }

    public function comments()
    {
        return $this->hasMany(PartiesComments::class, 'party_id', 'id')
            ->orderBy('created_at', 'desc');
    }

    public function ratings()
    {
        return $this->hasMany(PartiesRatings::class, 'party_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
