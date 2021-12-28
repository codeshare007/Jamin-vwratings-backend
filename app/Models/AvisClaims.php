<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string $string1, $id)
 */
class AvisClaims extends Model
{
    use HasFactory;

    protected $with = ['avi'];

    protected $fillable = ['user_id', 'avis_id', 'claimed_until'];

    public $timestamps = false;

    public function getClaimedUntilAttribute()
    {
        return [
            'now' => Carbon::now(),
            'until' => Carbon::parse($this->attributes['claimed_until'])
        ];
    }

    public function avi()
    {
        return $this->hasOne(Avi::class, 'id', 'avis_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
