<?php

namespace App\Models;

use App\Scopes\IsDeletedScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'manager_id', "image_url"
    ];
    protected static function booted()
    {
        static::addGlobalScope(new IsDeletedScope);
    }

    public function user()
    {
        return $this->belongsTo(User::class, "manager_id");
    }

    public function manager()
    {
        return $this->belongsTo(User::class, "manager_id");
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
