<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsUserAction extends Model
{
    protected $fillable = [
        'news_id',
        'user_id',
        'is_liked',
        'is_bookmarked'
    ];
}
