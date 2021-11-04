<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static firstOrCreate(array $array)
 * @method static paginate(int $int)
 */
class AvisComments extends Model
{
    use HasFactory;

    public $with = ['attachments'];

    protected $fillable = [
        'user_id',
        'avis_id',
        'content',
        'opinion'
    ];

    public function attachments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AvisCommentsAttachments::class, 'comment_id', 'id');
    }
}
