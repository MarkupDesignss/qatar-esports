<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DashboardImage extends Model
{
    protected $table = 'dashboard_images';
    protected $fillable = ['image1', 'image2'];
}
