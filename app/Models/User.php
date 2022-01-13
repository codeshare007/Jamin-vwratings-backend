<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Notifications\MailResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @method static firstOrCreate(array $array)
 * @method static paginate(int $int)
 * @method static findOrFail($id)
 * @method static where(string $string, string $string1, mixed $get)
 * @property mixed $username
 * @property mixed $password
 * @property mixed $email
 * @property mixed id
 * @property mixed role
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 1;
    const ROLE_USER = 2;
    const ROLE_USER_LIMITED = 3;

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
    public function notes(): HasMany
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
    public function getJWTCustomClaims(): array
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
    public function favoriteAvis(): HasMany
    {
        return $this->hasMany(UsersFavoriteAvis::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function favoriteParties(): HasMany
    {
        return $this->hasMany(UsersFavoriteParties::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function avisClaimed(): HasMany
    {
        return $this->hasMany(AvisClaims::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function partiesClaimed(): HasMany
    {
        return $this->hasMany(PartiesClaims::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function avisComments(): HasMany
    {
        return $this->hasMany(AvisComments::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function avis(): HasMany
    {
        return $this->hasMany(Avi::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function avisRated(): HasMany
    {
        return $this->hasMany(AvisRatings::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function parties(): HasMany
    {
        return $this->hasMany(Parties::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function partiesRated(): HasMany
    {
        return $this->hasMany(PartiesRatings::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function partiesComments(): HasMany
    {
        return $this->hasMany(PartiesComments::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(UsersNotifications::class, 'user_id', 'id');
    }


}
