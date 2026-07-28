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
                'type'    => 'terms',
                'title'   => 'Terms of Service',
                'content' => 'Default Terms content...',
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