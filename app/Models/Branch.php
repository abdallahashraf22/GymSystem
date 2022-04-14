<?php

namespace App\Models;

use App\Scopes\IsDeletedScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $fillable = [
        "id",
        "name",
        "city_id",
        "isDeleted",
        "img"
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new IsDeletedScope);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function cityManager()
    {
        return $this->hasOneThrough(User::class, City::class, "manager_id", 'city_id');
    }


    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }
}
