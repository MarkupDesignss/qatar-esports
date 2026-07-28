<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FooterSetting;
use Illuminate\Http\Request;

class FooterApiController extends Controller
{
    /**
     * Get footer settings
     */
    public function index()
    {
        $footer = FooterSetting::getSettings();

        return response()->json([
            'success' => true,
            'message' => 'Footer settings fetched successfully',
            'data' => [
                'tagline'         => $footer->tagline,
                'description'     => $footer->description,
                'copyright_text'  => $footer->copyright_text,
                'discord_url'     => $footer->discord_url,
                'youtube_url'     => $footer->youtube_url,
                'instagram_url'   => $footer->instagram_url,
                'twitter_url'     => $footer->twitter_url,
                'email'           => $footer->email,
                'contact_phone'   => $footer->contact_phone,
                'whatsapp_number'   => $footer->whatsapp_number,
                'contact_address' => $footer->contact_address,
            ]
        ]);
    }
}