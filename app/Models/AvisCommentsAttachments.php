<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvisCommentsAttachments extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'filename',
        'path',
        'type'
    ];

    /**
     * @return HasOne
     */
    public function comment()
    {
        return $this->hasOne(AvisComments::class, 'id', 'comment_id');
    }
}
