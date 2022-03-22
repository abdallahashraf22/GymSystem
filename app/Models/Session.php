<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    public function coaches(){
        $this->belongsToMany(Coach::class);
    }

    public function users(){
        $this->belongsToMany(User::class);
    }

    public function branch(){
        $this->belongsTo(Branch::class);
    }
}
