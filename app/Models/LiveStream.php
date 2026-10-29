<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveStream extends Model
{
    protected $fillable = [
        'tournament_id',
        'game_id',
        'platform',
        'channel_name',
        'language',
        'video_url',
        'is_live',
        'viewer_count',
        'started_at',
        'last_synced_at',
    ];

    protected $casts = [
        'started_at'     => 'datetime',
        'last_synced_at' => 'datetime',
        'is_live'        => 'boolean',
    ];

    /* ---------------- Computed is_live based on started_at ---------------- */

    public function getIsLiveAttribute($value): bool
    {
        return $this->started_at && $this->started_at->lte(now());
    }

    public function getStatusAttribute(): string
    {
        return $this->is_live ? 'live' : 'upcoming';
    }

    /* ---------------- Scopes ---------------- */

    public function scopeLive($query)
    {
        return $query->whereNotNull('started_at')
                     ->where('started_at', '<=', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('started_at')
              ->orWhere('started_at', '>', now());
        });
    }
    
    // Model mein
    public function scopeLiveOrUpcoming($query)
    {
        return $query->whereNotNull('started_at');
    }

    /* ---------------- Relations ---------------- */

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }
}