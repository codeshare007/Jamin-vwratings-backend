<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    /**
     * @param $query
     * @return mixed
     */
    public function scopeLatestComments($query)
    {
        return $query->has('comments')
            ->select([
                'avis.id',
                'avis.name',
                'avis_comments.id as avis_comment_id',
                DB::raw('MAX(CAST(avis_comments.created_at AS CHAR)) as comment_created_at')])
            ->join('avis_comments', function ($join) {
                $join->on('avis.id', '=', 'avis_comments.avis_id');
            })
            ->groupBy('avis.id')
            ->orderBy('comment_created_at', 'desc');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeLatestAttachments($query)
    {
        return $query->has('comments')
            ->select([
                'avis.id',
                'avis.name',
                'avis_comments.id as avis_comment_id',
                DB::raw('COUNT(avis_comments_attachments.id) as attachments_count'),
                DB::raw('MAX(CAST(avis_comments_attachments.created_at AS CHAR)) as attachment_created_at')])
            ->join('avis_comments', function ($join) {
                $join->on('avis.id', '=', 'avis_comments.avis_id')
                    ->join('avis_comments_attachments', function($a) {
                   $a->on('avis_comments_attachments.comment_id', '=', 'avis_comments.id');
                });
            })
            ->groupBy('avis.id')
            ->orderBy('attachment_created_at', 'desc')
            ->having('attachments_count', '>', 0);
    }

    public function scopeMostRated($query)
    {
        return $query->has('ratings')
            ->select([
               'avis.id',
               'avis.name',
               DB::raw('COUNT(avis_ratings.id) as avis_rating_amount')
            ])->join('avis_ratings', function($join) {
                $join->on('avis.id', '=', 'avis_ratings.avis_id');
            })->groupBy('avis.id')
            ->orderBy('avis_rating_amount', 'desc');
    }

    public function scopeMostCommented($query)
    {
        return $query->has('comments')
            ->select([
                'avis.id',
                'avis.name',
                DB::raw('COUNT(avis_comments.id) as avis_comments_amount')
            ])->join('avis_comments', function($join) {
                $join->on('avis.id', '=', 'avis_comments.avis_id');
            })->groupBy('avis.id')
            ->orderBy('avis_comments_amount', 'desc');
    }

    public function scopeTopRated($query)
    {
        return $query->has('ratings')
            ->select([
                'avis.id',
                'avis.name',
                DB::raw('AVG(avis_ratings.rating) as avis_average_rating'),
                DB::raw('COUNT(avis_ratings.id) as avis_ratings_amount')
            ])->join('avis_ratings', function($join) {
                $join->on('avis.id', '=', 'avis_ratings.avis_id');
            })->groupBy('avis.id')
            ->having('avis_ratings_amount', '>', 40)
            ->orderByRaw('avis_average_rating DESC');
    }

    public function scopeLowRated($query)
    {
        return $query;
    }


    public function scopeRecentRated($query)
    {
        // not used and need to be improved

        return $query
            ->has('ratings')
            ->select([
                'avis.id',
                'avis.name'
            ])
            ->join('avis_ratings', 'avis_ratings.avis_id', '=', 'avis.id')
            ->groupBy(DB::raw('`avis_ratings`.`updated_at`'))
            ->orderBy(DB::raw('`avis_ratings`.`updated_at`'), 'desc');
    }

    public function scopeAverageRating($query, $operator, $rating, $sort = 'DESC')
    {
        // not used and need to be improved

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
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
        return $this->hasMany(AvisComments::class, 'avis_id', 'id');
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
