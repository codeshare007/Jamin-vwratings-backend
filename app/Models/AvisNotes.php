<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvisNotes extends Model
{
    use HasFactory;

    protected $fillable = [
        'avis_id',
        'content',
        'comment'
    ];
}
