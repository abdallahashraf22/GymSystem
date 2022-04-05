<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'user_id',
        'branch_id',
        'enrollement_price',
    ];

    protected $table = 'packages_users_branches';
}
