<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSetting;
use Illuminate\Http\Request;

class PageSettingController extends Controller
{
    /**
     * Show all managed pages (Privacy, Terms, Cookie, Signup, Signin, Esports Signup).
     */
    public function index()
    {
        $privacy       = PageSetting::getPrivacy();
        $terms         = PageSetting::getTerms();
        $cookie        = PageSetting::getCookie();
        $signup        = PageSetting::getSignup();
        $signin        = PageSetting::getSignin();
        $signupEsports = PageSetting::getSignupEsports();

        return view('admin.pages.index', compact(
            'privacy',
            'terms',
            'cookie',
            'signup',
            'signin',
            'signupEsports'
        ));
    }

    /**
     * Show the edit form for a page (resolved by slug).
     */
    public function edit($slug)
    {
        $page = PageSetting::where('slug', $slug)->firstOrFail();

        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified page (resolved by id).
     */
    public function update(Request $request, $id)
    {
        $page = PageSetting::findOrFail($id);

        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $page->update($data);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', ucfirst(str_replace('_', ' ', $page->type)) . ' page updated successfully!');
    }
}