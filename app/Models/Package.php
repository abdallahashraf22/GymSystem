<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{

    protected $fillable = [
        'name',
        'price',
        'number_of_sessions'
    ];

    use HasFactory;

    public function users()
    {
        $this->belongsToMany(User::class);
    }

    public function branches()
    {
        $this->belongsToMany(Branch::class);
    }
}
