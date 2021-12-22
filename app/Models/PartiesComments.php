<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartiesComments extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'avis_id',
        'content',
        'opinion'
    ];

    public function attachments()
    {
        return $this->hasMany(PartiesCommentsAttachments::class, 'comment_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'party_id');
    }

    public function party()
    {
        return $this->hasOne(Parties::class, 'id', 'party_id');
    }


}
