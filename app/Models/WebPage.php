<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebPage extends Model
{
    protected $table = 'web_pages';

    protected $fillable = [
        'title',
        'slug',
        'banner_image',
        'status',
    ];
}
