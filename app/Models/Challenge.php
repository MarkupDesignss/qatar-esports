<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    protected $fillable = ['heading', 'content', 'image', 'video_url','thumbnail'];
}
