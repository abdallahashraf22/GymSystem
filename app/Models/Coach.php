<?php

namespace App\Models;

use App\Scopes\IsDeletedScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new IsDeletedScope);
    }

    protected $fillable = [
        'name',
        "isDeleted",
        "image_url"
    ];

    public function sessions()
    {
        return $this->belongsToMany(Session::class);
    }
}
