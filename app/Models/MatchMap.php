<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchMap extends Model
{
    protected $table = 'match_maps';

    protected $fillable = [
        'match_id',
        'map_id',
        'map_order',
        'team1_side',
        'team2_side',
        'winner_team_id'
    ];
        public function map()
    {
        return $this->belongsTo(\App\Models\Map::class, 'map_id');
    }

     public function match()
    {
        return $this->belongsTo(\App\Models\Matchs::class, 'match_id');
    }
}