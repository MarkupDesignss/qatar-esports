<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'tournament_id',
        'title',
        'description',
        'type',
        'type_id', 
        'thumbnail',
        'like_count',
        'bookmark_count',
        'status'
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
    
    public function type()
    {
        return $this->belongsTo(NewsType::class, 'type_id');
    }
}
