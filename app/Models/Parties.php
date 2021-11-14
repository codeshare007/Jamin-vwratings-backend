<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Parties extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'name',
        'created_at',
        'updated_at'
    ];

    public function scopeLatestComments($query)
    {
        return $query->has('comments')
            ->leftJoin('parties_comments', 'parties_comments.party_id', '=', 'parties.id')
            ->select(['parties.id', 'parties.name', DB::raw('COUNT(parties_comments.id) as parties_count')])
            ->groupBy(DB::raw('`parties_comments`.`party_id`'))
            ->orderBy(DB::raw('`parties_comments`.`created_at`'), 'desc')
            ->orderBy('parties_count', 'DESC');
    }

    public function scopeLatestAttachments($query)
    {
        return $query->has('comments')
            ->leftJoin('parties_comments', 'parties_comments.party_id', '=', 'parties.id')
            ->rightJoin('parties_comments_attachments', 'parties_comments_attachments.comment_id', '=', 'parties_comments.id')
            ->select(['parties.id', 'parties.name', DB::raw('COUNT(parties_comments_attachments.id) as attachments_count')])
            ->groupBy(DB::raw('`parties_comments`.`party_id`'))
            ->orderBy(DB::raw('`parties_comments_attachments`.`created_at`'), 'desc')
            ->having('attachments_count', '>', '0')
            ->orderBy('attachments_count', 'DESC');
    }

    public function scopeRecentRated($query)
    {
        return $query->distinct()->has('ratings')
            ->leftJoin('parties_ratings', 'parties_ratings.party_id', '=', 'parties.id')
            ->select(['parties.id', 'parties.name'])
            ->groupBy(DB::raw('`parties_ratings`.`updated_at`'))
            ->orderBy(DB::raw('`parties_ratings`.`updated_at`'), 'desc');
    }

    public function scopeAverageRating($query, $operator, $rating, $sort = 'DESC')
    {
        return $query->with('ratings')
            ->leftJoin('parties_ratings', 'parties_ratings.party_id', '=', 'parties.id')
            ->select(['parties.id', 'parties.name', DB::raw('AVG(parties_ratings.rating) as ratings_average')])
            ->groupBy(DB::raw('`parties_ratings`.`party_id`'))
            ->having('ratings_average', $operator, $rating)
            ->orderBy('ratings_average', $sort);
    }

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
