<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvisInterviews extends Model
{
    use HasFactory;

    public $fillable = ['content'];

    public $with = ['avi'];

    public function avi()
    {
        return $this->hasOne(Avi::class, 'id', 'avis_id');
    }
}
