<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchHighlightImage extends Model
{
    protected $table = 'match_highlight_images';
    protected $fillable = ['image', 'sort_order'];

    public function match()
    {
        return $this->belongsTo(MatchHighlight::class);
    }
}