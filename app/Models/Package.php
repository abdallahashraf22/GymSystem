<?php

namespace App\Models;

use App\Scopes\IsDeletedScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{

    protected $fillable = [
        'name',
        'price',
        'number_of_sessions',
        'image',
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

    use HasFactory;

    public function users()
    {
        $this->belongsToMany(User::class, "packages_users_branches");
    }

    public function branches()
    {
        $this->belongsToMany(Branch::class);
    }
}
