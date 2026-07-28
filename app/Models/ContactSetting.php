<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    protected $fillable = [
        'get_in_touch_desc',
        'get_in_touch_title',
        'partnership_title',
        'partnership_description',
        'partnership_email',
        'sales_title',
        'sales_description',
        'sales_email',
        'technical_title',
        'technical_description',
        'technical_email',
        'contact_image',
    ];

    public static function getSettings()
    {
        return self::first() ?? self::create([
            'partnership_title' => 'Partnership',
            'partnership_description' => 'Cooperation and partnership opportunities for brands and esports communities.',
            'partnership_email' => 'jackalblushi@qecgg.com',
            'sales_title' => 'Sales Department',
            'sales_description' => 'Consulting, reports and premium esports data solutions.',
            'sales_email' => 'smartgama@qecgg.com',
            'technical_title' => 'Technical Support',
            'technical_description' => 'Help related to payments, tournaments and technical',
            'technical_email' => 'info@qecgg.com',
        ]);
    }
}