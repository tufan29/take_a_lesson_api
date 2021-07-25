<?php
namespace App\Models\teacher;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Teacher extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    protected $table = 'teacher';
    protected $primaryKey = 't_id';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $fillable = [
        'uid',
        'name',
        'phone',
        'email',
        'email_verified_at',
        'password',
        'address',
        'about',
        'approve',
        'is_verified',
        'profile_pic',
        'verification_code'
    ];
    protected $hidden = [
        'password',
        'verification_code',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime:d-m-Y H:m:i',
    ];
    //The model's default values for attributes.
    protected $attributes = [
        'approve'=>'0',
        'is_verified'=>'0',
    ];

    public static function getProfilePicAttribute($value)
    {
        return url('')."/".$value;
    }
    public function getUidAttribute($value)
    {
        return strtoupper($value);
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
}
