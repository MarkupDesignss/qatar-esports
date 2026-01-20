<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogoController extends Controller
{
    public function index()
    {
        $logo = Logo::first();
        return view('admin.logo.index', compact('logo'));
    }

    public function edit()
    {
        $logo = Logo::firstOrFail();
        return view('admin.logo.edit', compact('logo'));
    }

    public function update(Request $request)
    {
        $logo = Logo::firstOrFail();

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp',
        ]);

        if ($request->hasFile('image')) {

            // delete old image properly
            if ($logo->image && Storage::disk('public')->exists($logo->image)) {
                Storage::disk('public')->delete($logo->image);
            }

            // store new image
            $data['image'] = $request->file('image')->store('logos', 'public');
        }

        $logo->update($data);

        return redirect()
            ->route('admin.logo.index')
            ->with('success', 'Logo updated successfully.');
    }
}
