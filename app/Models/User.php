<?php

namespace App\Models;

use App\Scopes\IsDeletedScope;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laravel\Cashier\Billable;



class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, Billable;


    protected $fillable = [
        'name',
        'email',
        'password',
        "role",
        "national_id",
        "image_url",
        "isDeleted",
        "remember_token",
        "branch_id"
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
        return $this->belongsToMany(Session::class, 'user_session')->withTimestamps();
    }

    public function city()
    {
        return $this->hasOne(City::class, "manager_id", "id");
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, "packages_users_branches");
    }




    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    public function getJWTCustomClaims()
    {

        return [
            "email" => $this->email,
            "role" => $this->role,
            "city_id" => $this->city ? $this->city->id : null,
            "branch_id" => $this->branch_id
        ];
    }
}
