<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

//Añadimos la clase JWTSubject 
use Tymon\JWTAuth\Contracts\JWTSubject;

//Añadimos la implementación de JWT en nuestro modelo
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullName', 'email', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /*
        Añadiremos estos dos métodos
    */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

        /**
     * Find a user using the user identifier in the subject claim.
     *
     * @param bool|string $token
     *
     * @return mixed
     */

    public function toUser($token = false)
    {
        $payload = $this->getPayload($token);

        if (! $user = $this->user->getBy($this->identifier, $payload['sub'])) {
            return false;
        }

        return $user;
    }
}