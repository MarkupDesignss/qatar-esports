<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LiveStream extends Model
{
    use HasFactory;

    protected $table = 'live_streams';

    /**
     * Mass assignable fields
     */
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

    /**
     * Type casting
     */
    protected $casts = [
        'is_live' => 'boolean',
        'viewer_count' => 'integer',
        'started_at' => 'datetime',
        'last_synced_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Accessor: Generate embed URL dynamically
     * (Recommended instead of hardcoding in DB)
     */
    // public function getEmbedUrlAttribute()
    // {
    //     if ($this->platform === 'twitch') {
    //         $parent = config('app.frontend_domain'); // example: app.yoursite.com
    //         return "https://player.twitch.tv/?channel={$this->channel_name}&parent={$parent}";
    //     }

    //     if ($this->platform === 'youtube') {
    //         return "https://www.youtube.com/embed/{$this->channel_name}";
    //     }

    //     return null;
    // }
}