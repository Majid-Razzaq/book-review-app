<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{

    public function reviews(){
        return $this->hasMany(Review::class);

    }

    use HasFactory;
}
