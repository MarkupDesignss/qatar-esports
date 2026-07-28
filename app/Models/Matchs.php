<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matchs extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'tournament_id',
        'team1_id',
        'team2_id',
        'round',
        'best_of',
        'match_order',
        'winner_id',
        'status',
        'played_at',
        'match_date',
        'match_time',
        'banner',
        'team1_name',
        'team2_name',
        'winner_team_name',
    ];
    public function team1()
    {
        return $this->belongsTo(TournamentRegistration::class, 'team1_id');
    }

    public function team2()
    {
        return $this->belongsTo(TournamentRegistration::class, 'team2_id');
    }

    public function winner()
    {
        return $this->belongsTo(TournamentRegistration::class, 'winner_id');
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function maps()
    {
        return $this->hasMany(\App\Models\MatchMap::class, 'match_id');
    }
}