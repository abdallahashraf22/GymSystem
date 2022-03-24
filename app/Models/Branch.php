<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $fillable = ["id", "name", "city_id"];

    public function city(){
        $this->belongsTo(City::class);
    }

    public function user(){
        $this->belongsTo(User::class);
    }

    public function sessions(){
        $this->hasMany(Session::class);
    }

    public function users(){
        $this->hasMany(User::class);
    }

    public function packages(){
        $this->hasMany(Package::class);
    }
}
