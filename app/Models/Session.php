<?php

namespace App\Models;

use App\Scopes\IsDeletedScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'branch_id',
        'start_time',
        'end_time',
        'coach_id',
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

    public function coaches()
    {
        return $this->belongsToMany(Coach::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_session')->withTimestamps();
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
