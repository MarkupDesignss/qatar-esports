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
        'team_name',
        'team_tag',
        'team_logo',
        'is_captain',
        'invite_link',
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
}