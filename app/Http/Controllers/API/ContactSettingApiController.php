<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ContactSetting;

class ContactSettingApiController extends Controller
{
    public function index()
    {
        $settings = ContactSetting::getSettings();
        return response()->json([
            'success' => true,
            'data' => [
                'Get_in_touch' => [
                    'title' => $settings->get_in_touch_title,
                    'description' => $settings->get_in_touch_desc,
                ],
                'partnership' => [
                    'title' => $settings->partnership_title,
                    'description' => $settings->partnership_description,
                    'email' => $settings->partnership_email,
                ],
                'sales' => [
                    'title' => $settings->sales_title,
                    'description' => $settings->sales_description,
                    'email' => $settings->sales_email,
                ],
                'technical' => [
                    'title' => $settings->technical_title,
                    'description' => $settings->technical_description,
                    'email' => $settings->technical_email,
                ],
                'image' => $settings->contact_image ? asset('storage/'.$settings->contact_image) : null,
            ]
        ]);
    }
}