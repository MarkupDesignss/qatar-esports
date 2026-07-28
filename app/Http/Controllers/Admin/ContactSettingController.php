<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContactSettingController extends Controller
{
    public function index()
    {
        $settings = ContactSetting::getSettings();
        return view('admin.contact-settings.index', compact('settings'));
    }

    public function edit()
    {
        $settings = ContactSetting::getSettings();
        return view('admin.contact-settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = ContactSetting::getSettings();

        $data = $request->validate([
            'get_in_touch_title' => 'nullable|string|max:255',
            'get_in_touch_desc' => 'nullable|string|max:255',
            
            'partnership_title' => 'nullable|string|max:255',
            'partnership_description' => 'nullable|string',
            'partnership_email' => 'nullable|email|max:255',

            'sales_title' => 'nullable|string|max:255',
            'sales_description' => 'nullable|string',
            'sales_email' => 'nullable|email|max:255',

            'technical_title' => 'nullable|string|max:255',
            'technical_description' => 'nullable|string',
            'technical_email' => 'nullable|email|max:255',
        ]);

        $settings->update($data);

        return redirect()->route('admin.contact-settings.index')
            ->with('success', 'Contact settings updated successfully!');
    }
}