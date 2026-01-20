<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchHighlight extends Model
{
    protected $table = "match_highlights";
    protected $guarded = [];
    
        public function images()
    {
        return $this->hasMany(MatchHighlightImage::class);
    }

    public function contents()
    {
        return $this->hasMany(MatchHighlightContent::class)
            ->orderBy('sort_order');
    }
}
