<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static firstOrCreate(array $array)
 * @method static paginate(int $int)
 * @method static findOrFail($id)
 */
class AvisComments extends Model
{
    use HasFactory;

    protected $table = 'avis_comments';

    protected $fillable = [
        'id',
        'user_id',
        'avis_id',
        'content',
        'opinion',
        'created_at',
        'updated_at'
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
