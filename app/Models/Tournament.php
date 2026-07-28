<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Tournament extends Model
{
    
    protected $guarded =[];

    protected $casts = [
        'registration_start' => 'datetime',
        'registration_end' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'social_links' => 'array',
    ];
    
    public function getStreamUrlAttribute($value)
    {
        return $value ?? null;
    }

    public function getSocialPlatformsAttribute(): array
    {
        return $this->social_links ? array_keys($this->social_links) : [];
    }

    public function hasSocialLink(string $platform): bool
    {
        return $this->social_links && isset($this->social_links[$platform]);
    }
    
    public function getSocialLink(string $platform): ?string
    {
        return $this->social_links[$platform] ?? null;
    }

    public function getStatusAttribute()
    {
        if (!$this->start_date) {
            return 'upcoming';
        }
    
        $now = Carbon::now();
    
        // start_date and end_date are already Carbon instances thanks to $casts
        if ($now->lt($this->start_date)) {
            return 'upcoming';
        }
    
        if ($this->end_date && $now->gt($this->end_date)) {
            return 'completed';
        }
    
        return 'live';
    }


    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function registrations()
    {
        return $this->hasMany(TournamentRegistration::class);
    }

    public function liveStreams()
    {
        return $this->hasMany(LiveStream::class);
    }
    
    public function matches()
    {
        return $this->hasMany(\App\Models\Matchs::class, 'tournament_id');
    }
    public function winner()
    {
        return $this->belongsTo(TournamentRegistration::class, 'winner_team_id');
    }


}