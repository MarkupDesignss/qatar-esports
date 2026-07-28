<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    protected $table = 'maps';
    protected $fillable = [
        'game_id',
        'name',
        'slug',
        'image',
        'is_active'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
