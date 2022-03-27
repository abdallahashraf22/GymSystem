<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, "manager_id");
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
