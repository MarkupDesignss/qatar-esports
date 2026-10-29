<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'pending_email',
        'email_verified',
        'country_code',
        'mobile',
        'password',
        'otp',
        'otp_expires_at',
        'otp_sent_at',
        'status',
    ];

    /**
     * Hidden fields
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'email_verified' => 'boolean',
        'otp_expires_at' => 'datetime',
        'otp_sent_at'    => 'datetime',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    public function tournamentRegistrations()
    {
        return $this->hasMany(TournamentRegistration::class,'user_id');
    }
    
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }
    
    public function socialLinks()
    {
        return $this->hasOne(UserSocialLink::class, 'user_id');
    }
}