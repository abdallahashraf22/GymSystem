<?php

namespace App\Models;

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
        'coach_id'
        ];

    public function coaches(){
        return $this->belongsToMany(Coach::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function branch(){
        return $this->belongsTo(Branch::class);
    }
}
