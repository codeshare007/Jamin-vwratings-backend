<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static firstOrCreate(array $array)
 * @method static paginate(int $int)
 */
class AvisComments extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'avis_id',
        'content',
        'opinion'
    ];

    /**
     * @return HasMany
     */
    public function attachments()
    {
        return $this->hasMany(AvisCommentsAttachments::class, 'comment_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @return HasOne
     */
    public function avi()
    {
        return $this->hasOne(Avi::class, 'id', 'avis_id');
    }
}
