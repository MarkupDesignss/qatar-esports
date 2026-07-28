<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSetting;
use Illuminate\Http\Request;

class PageSettingController extends Controller
{
    // Show both pages (Privacy & Terms)
    public function index()
    {
        $privacy = PageSetting::getPrivacy();
        $terms   = PageSetting::getTerms();
        $cookie   = PageSetting::getCookie();

        return view('admin.pages.index', compact('privacy', 'terms','cookie'));
    }

    // Edit form (using slug)
    public function edit($slug)
    {
        $page = PageSetting::where('slug', $slug)->firstOrFail();
        return view('admin.pages.edit', compact('page'));
    }

    // Update page
    public function update(Request $request, $id)
    {
        $page = PageSetting::findOrFail($id);

        $data = $request->validate([
            'title'   => 'nullable|string|max:255',
            'content' => 'nullable|string',
        ]);

        $page->update($data);

        return redirect()->route('admin.pages.index')
            ->with('success', ucfirst($page->type) . ' page updated successfully!');
    }
}