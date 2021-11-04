<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static firstOrCreate(array $array)
 * @method static findOrFail($id)
 * @method static find($id)
 * @method static select(string[] $array)
 */
class Avi extends Model
{
    use HasFactory;

    public $appends = ['average_rating', 'user_rating'];

    protected $fillable = [
        'user_id',
        'name'
    ];

    public function getUserRatingAttribute()
    {
        if (auth()->check()) {
            if ($rating = $this->ratings()->where([
                'avis_id' => $this->attributes['id'],
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

    public function interview()
    {
        return $this->hasOne(AvisInterviews::class, 'avis_id', 'id');
    }

    public function notes()
    {
        return $this->hasMany(AvisNotes::class, 'avis_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(AvisComments::class, 'avis_id', 'id')
            ->orderBy('created_at', 'desc');
    }

    public function ratings()
    {
        return $this->hasMany(AvisRatings::class, 'avis_id', 'id');
    }
}
