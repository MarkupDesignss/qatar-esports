<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PreviousWork extends Model
{
    protected $fillable = [
        'category',
        'title',
        'event_date',
        'description',
        'image',
        'video_url',
        'status'
    ];
}
