<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'banner',
        'platform',
        'status'
    ];

    // Game has many tournaments
    public function tournaments()
    {
        return $this->hasMany(Tournament::class);
    }
}