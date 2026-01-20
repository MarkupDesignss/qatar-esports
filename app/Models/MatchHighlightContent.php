<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchHighlightContent extends Model
{
    protected $table = 'match_highlight_contents';

    protected $fillable = ['heading', 'content', 'sort_order'];

    public function match()
    {
        return $this->belongsTo(MatchHighlight::class);
    }
}
