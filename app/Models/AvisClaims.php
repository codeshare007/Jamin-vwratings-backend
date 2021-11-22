<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvisClaims extends Model
{
    use HasFactory;

    protected $with = ['avi'];

    protected $fillable = ['user_id', 'avis_id', 'claimed_until'];

    public $timestamps = false;

    public function avi()
    {
        return $this->hasOne(Avi::class, 'id', 'avis_id');
    }
}
