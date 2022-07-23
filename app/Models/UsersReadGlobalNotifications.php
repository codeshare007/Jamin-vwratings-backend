<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(array $array)
 */
class UsersReadGlobalNotifications extends Model
{
    const STATUS_DELECTED = 1;
    const STATUS_READ = 2;

    use HasFactory;

    protected $fillable = ['user_id', 'notification_id', 'status'];

    public $timestamps = false;

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'notification_id');
    }
}
