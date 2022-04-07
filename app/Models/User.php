<?php

namespace App\Models;

use App\Scopes\IsDeletedScope;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;



class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
        "role",
        "national_id",
        "image_url",
        "isDeleted",
        "remember_token"
    ];


    protected static function booted()
    {
        static::addGlobalScope(new IsDeletedScope);
    }


    protected $hidden = [
        'password',
        'remember_token',
        "isDeleted"
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sessions()
    {
        return $this->belongsToMany(Session::class);
    }

    public function city()
    {
        return $this->hasOne(City::class, "manager_id", "id");
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }




    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    public function getJWTCustomClaims()
    {
        return [
            "email" => $this->email,
            "role" => $this->role
        ];
    }
}
