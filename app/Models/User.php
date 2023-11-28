<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Ramsey\Uuid\Uuid;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    /**
     * get user's img link from imgFileName
     * 
     * @return string|null
     */
    static public function getImgLink(string|null $fileName)
    {
        return $fileName ? route('user.img', ['fileName' => $fileName]) : null;
    }

    /**
     * returns img file name
     * 
     * @return string
     */
    static public function getImgFileName($id, $user_uuid, $extension)
    {
        return "$id" . '_' . "$user_uuid." . $extension;
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public const IMG_PATH = 'img-user'; // /...filename.png/jpg/jpeg
    public const RULES = [
        "first_name" => "required|string",
        "last_name" => "string",
        "email_register" => "required|email|unique:users,email",
        "email" => "required|email",
        "password" => "required|string|min:8",
    ];

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Generate a new UUID for the model.
     */
    public static function newUuid(): string
    {
        return (string) Uuid::uuid4();
    }

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
