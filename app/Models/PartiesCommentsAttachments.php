<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartiesCommentsAttachments extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'path',
        'type'
    ];
}
