<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @method static firstOrCreate(array $array)
 */
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
        return $query
            ->leftJoin('parties_comments', 'parties.id', '=', 'parties_comments.party_id')
            ->select(['parties.*'])
            ->groupBy('comment_id')
            ->distinct()
            ->orderBy('parties_comments.created_at', 'desc');
    }

    public function scopeLatestAttachments($query)
    {
        return $query
            ->leftJoin('parties_comments', 'parties.id', '=', 'parties_comments.party_id')
            ->leftJoin('parties_comments_attachments', 'parties_comments_attachments.comment_id', '=', 'parties_comments.id')
            ->select(['parties.*', DB::raw('COUNT(parties_comments_attachments.id) as attachments_count')])
            ->groupBy('comment_id')
            ->distinct()
            ->orderBy('parties_comments_attachments.created_at', 'desc')
            ->having('attachments_count', '>', '0');
    }

    public function scopeRecentRated($query)
    {
        return $query->has('comments')
            ->rightJoin('parties_comments', 'parties_comments.party_id', '=', 'parties.id')
            ->rightJoin('parties_comments_attachments', 'parties_comments_attachments.comment_id', '=', 'parties_comments.id')
            ->select(['parties.id', 'parties.name', DB::raw('COUNT(parties_comments_attachments.id) as attachments_count')])
            ->groupBy(DB::raw('`parties_comments`.`party_id`'))
            ->orderBy(DB::raw('`parties_comments`.`created_at`'), 'desc')
            ->having('attachments_count', '>', '0');
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

    public function claim()
    {
        return $this->hasOne(PartiesClaims::class, 'party_id', 'id');
    }
}
