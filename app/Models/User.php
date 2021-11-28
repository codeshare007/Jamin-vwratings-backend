<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Models\AvisComments;
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
     * @var string[]
     */
    protected $fillable = [
        'id',
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
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return HasMany
     */
    public function notes()
    {
        return $this->hasMany(UsersNotes::class, 'user_id', 'id');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Override the mail body for reset password notification mail.
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordNotification($token, $this->attributes['email']));
    }

    /**
     * @return HasMany
     */
    public function avisClaimed()
    {
        return $this->hasMany(AvisClaims::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function partiesClaimed()
    {
        return $this->hasMany(PartiesClaims::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(AvisComments::class, 'user_id', 'id');
    }

}
