<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageSetting extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'page_settings';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'type',
        'slug',
        'title',
        'content',
    ];

    /**
     * Get a page by its slug.
     */
    public static function getBySlug(string $slug)
    {
        return self::where('slug', $slug)->first();
    }

    /**
     * Get the privacy policy page (singleton).
     */
    public static function getPrivacy()
    {
        return self::firstOrCreate(
            ['slug' => 'privacy-policy'],
            [
                'type'    => 'privacy',
                'title'   => 'Privacy Policy',
                'content' => 'Default Privacy Policy content...',
            ]
        );
    }

    /**
     * Get the terms of service page (singleton).
     */
    public static function getTerms()
    {
        return self::firstOrCreate(
            ['slug' => 'terms-of-service'],
            [
                'type'    => 'terms',
                'title'   => 'Terms of Service',
                'content' => 'Default Terms content...',
            ]
        );
    }

    /**
     * Get the cookie policy page (singleton).
     */
    public static function getCookie()
    {
        return self::firstOrCreate(
            ['slug' => 'cookie-policy'],
            [
                'type'    => 'cookie',
                'title'   => 'Cookie Policy',
                'content' => 'Default Cookie Policy content...',
            ]
        );
    }

    /**
     * Get the signup page (singleton).
     */
    public static function getSignup()
    {
        return self::firstOrCreate(
            ['slug' => 'signup'],
            [
                'type'    => 'signup',
                'title'   => 'Sign Up',
                'content' => 'Default Signup page content...',
            ]
        );
    }

    /**
     * Get the signin page (singleton).
     */
    public static function getSignin()
    {
        return self::firstOrCreate(
            ['slug' => 'signin'],
            [
                'type'    => 'signin',
                'title'   => 'Sign In',
                'content' => 'Default Signin page content...',
            ]
        );
    }

    /**
     * Get the "Sign Up To Esports QEC" page (singleton).
     */
    public static function getSignupEsports()
    {
        return self::firstOrCreate(
            ['slug' => 'sign-up-to-esports-qec'],
            [
                'type'    => 'signup_esports',
                'title'   => 'Sign Up To Esports QEC',
                'content' => 'Default Sign Up To Esports QEC content...',
            ]
        );
    }

    /**
     * If you want to use slug instead of id in routes.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}