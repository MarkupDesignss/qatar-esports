<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsType extends Model
{
    protected $fillable = ['slug', 'name', 'is_active', 'sort_order'];
}