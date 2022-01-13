<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail($id)
 * @method static create(array $array)
 */
class UsersNotifications extends Model
{

    const STATUS_UNREAD = 1;
    const STATUS_READ = 2;

    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'status'
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
