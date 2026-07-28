<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TournamentRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'user_id',
        'type',
        'name',
        'email',
        'phone',
        'status',
        'team_name',
        'team_tag',
        'team_logo',
        'is_captain',
        'invite_link',
         'prize_rank',
        'prize_amount',
        'prize_distributed_at',
        'is_prize_claimed',
    ];

    protected $casts = [
        'is_captain' => 'boolean',
    ];

    /* ==========================
     |  Relationships
     |==========================*/

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function team()
{
    return $this->belongsTo(Team::class, 'team_id'); // adjust foreign key if needed
}
}