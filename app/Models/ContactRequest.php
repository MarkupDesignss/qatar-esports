<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactRequest extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'subject',
        'message',
        'status'
    ];
}