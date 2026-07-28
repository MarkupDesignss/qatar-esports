<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchMapVeto extends Model
{
    protected $table = 'match_map_vetoes';
    protected $guarded = [];
    
    public function map()
{
    return $this->belongsTo(Map::class);
}

public function team()
{
    return $this->belongsTo(TournamentRegistration::class,'team_id');
}
}