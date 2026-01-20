<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'button_text',
        'button_link',
        'status',
        'sort_order'
    ];
}
