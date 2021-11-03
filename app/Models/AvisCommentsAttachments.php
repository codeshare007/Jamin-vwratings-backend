<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvisCommentsAttachments extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'path',
        'type'
    ];
}
