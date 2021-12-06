<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @method static firstOrCreate(array $array)
 * @method static findOrFail($id)
 * @method static find($id)
 * @method static select(string[] $array)
 * @method static paginate(int $int)
 */
class Avi extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'status',
        'name',
        'created_at',
        'updated_at'
    ];

    public function scopeLatestComments($query)
    {
        return $query
            ->has('comments')
            ->select(['avis.*'])
            ->join('avis_comments', 'avis.id', '=', 'avis_comments.avis_id')
            ->groupBy('avis_comments.avis_id')
            ->orderBy('avis_comments.created_at', 'desc');
    }

    public function scopeLatestAttachments($query)
    {
        return $query->has('comments')
            ->rightJoin('avis_comments', 'avis_comments.avis_id', '=', 'avis.id')
            ->rightJoin('avis_comments_attachments', 'avis_comments_attachments.comment_id', '=', 'avis_comments.id')
            ->select(['avis.id', 'avis.name', DB::raw('COUNT(avis_comments_attachments.id) as attachments_count')])
            ->groupBy(DB::raw('`avis_comments`.`avis_id`'))
            ->orderBy(DB::raw('`avis_comments`.`created_at`'), 'desc')
            ->having('attachments_count', '>', '0');
    }

    public function scopeRecentRated($query)
    {
        return $query->distinct()->has('ratings')
            ->leftJoin('avis_ratings', 'avis_ratings.avis_id', '=', 'avis.id')
            ->select(['avis.id', 'avis.name'])
            ->groupBy(DB::raw('`avis_ratings`.`updated_at`'))
            ->orderBy(DB::raw('`avis_ratings`.`updated_at`'), 'desc');
    }

    public function scopeAverageRating($query, $operator, $rating, $sort = 'DESC')
    {
        return $query->with('ratings')
            ->leftJoin('avis_ratings', 'avis_ratings.avis_id', '=', 'avis.id')
            ->select(['avis.id', 'avis.name', DB::raw('AVG(avis_ratings.rating) as ratings_average')])
            ->groupBy(DB::raw('`avis_ratings`.`avis_id`'))
            ->having('ratings_average', $operator, $rating)
            ->orderBy('ratings_average', $sort);
    }

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

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function claim()
    {
        return $this->hasOne(AvisClaims::class, 'avis_id', 'id');
    }
}
