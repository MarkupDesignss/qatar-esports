<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Tournament extends Model
{
    protected $fillable = [
        'game_id', 'title', 'slug', 'logo', 'banner', 'location',
        'format', 'team_size', 'status', 'visibility',
        'is_featured', 'is_registration_open', 'registration_start',
        'registration_end', 'start_date', 'end_date', 'start_time',
        'timezone', 'entry_fee', 'prize_pool', 'max_participants',
        'registered_participants', 'description', 'rules', 'created_by'
    ];

    protected $casts = [
        'registration_start' => 'datetime',
        'registration_end' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

     // Dynamic status accessor
    public function getStatusAttribute()
    {
        if (!$this->start_date) {
            return 'upcoming';
        }

        $now = Carbon::now();

        $start = Carbon::parse($this->start_date->format('Y-m-d') . ' ' . ($this->start_time ?? '00:00:00'));

        $end = $this->end_date ? Carbon::parse($this->end_date)->endOfDay() : null;

        if ($now->lt($start)) {
            return 'upcoming';
        }

        if ($end && $now->gt($end)) {
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


}