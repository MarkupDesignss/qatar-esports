<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $table = 'user_profiles';
    protected $guarded = [];
    
    public function profile()
{
    return $this->hasOne(UserProfile::class);
}
}
