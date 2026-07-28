<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    protected $fillable = ['welcome_heading', 'heading', 'content', 'image', 'video_url','thumbnail'];
}
