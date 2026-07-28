<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * (Laravel will automatically assume 'abouts' for model 'About')
     */
    protected $table = 'abouts';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'heading',
        'description',
        'badge',
        'image',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

}