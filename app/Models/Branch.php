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
        "isDeleted"
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
        $this->belongsTo(City::class);
    }


    public function sessions()
    {
        $this->hasMany(Session::class);
    }

    public function users()
    {
        $this->hasMany(User::class);
    }

    public function packages()
    {
        $this->hasMany(Package::class);
    }
}
