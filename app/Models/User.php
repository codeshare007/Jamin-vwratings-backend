<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Notifications\MailResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @method static firstOrCreate(array $array)
 * @method static paginate(int $int)
 * @method static findOrFail($id)
 * @property mixed $username
 * @property mixed $password
 * @property mixed $email
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 1;
    const ROLE_USER = 2;

    const STATUS_NEW = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'points',
        'ip_address',
        'referrer_name',
        'last_visit'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function notes()
    {
        return $this->hasMany(UsersNotes::class, 'user_id', 'id');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Override the mail body for reset password notification mail.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\MailResetPasswordNotification($token));
    }

    public function avisClaimed()
    {
        return $this->hasMany(AvisClaims::class, 'user_id', 'id');
    }

    public function partiesClaimed()
    {
        return $this->hasMany(PartiesClaims::class, 'user_id', 'id');
    }

}
