<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DashboardImage;
use Illuminate\Support\Facades\Storage;

class DashboardImageController extends Controller
{
    public function index()
    {
        $images = DashboardImage::first(); // Only one row for 2 images
        return view('admin.dashboard_images.index', compact('images'));
    }

    public function create()
    {
        return view('admin.dashboard_images.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image1' => 'required|image|mimes:jpg,jpeg,png',
            'image2' => 'required|image|mimes:jpg,jpeg,png',
        ]);

        $data = [];

        if ($request->hasFile('image1')) {
            $data['image1'] = $request->file('image1')->store('dashboard_images', 'public');
        }
        if ($request->hasFile('image2')) {
            $data['image2'] = $request->file('image2')->store('dashboard_images', 'public');
        }

        DashboardImage::create($data);

        return redirect()->route('admin.dashboard-images.index')->with('success', 'Images added successfully.');
    }

    public function edit(DashboardImage $dashboardImage)
    {
        return view('admin.dashboard_images.edit', compact('dashboardImage'));
    }

    public function update(Request $request, DashboardImage $dashboardImage)
    {
        $request->validate([
            'image1' => 'nullable|image|mimes:jpg,jpeg,png',
            'image2' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $data = [];

        if ($request->hasFile('image1')) {
            if ($dashboardImage->image1) {
                Storage::disk('public')->delete($dashboardImage->image1);
            }
            $data['image1'] = $request->file('image1')->store('dashboard_images', 'public');
        }

        if ($request->hasFile('image2')) {
            if ($dashboardImage->image2) {
                Storage::disk('public')->delete($dashboardImage->image2);
            }
            $data['image2'] = $request->file('image2')->store('dashboard_images', 'public');
        }

        $dashboardImage->update($data);

        return redirect()->route('admin.dashboard-images.index')->with('success', 'Images updated successfully.');
    }

    public function destroy(DashboardImage $dashboardImage)
    {
        if ($dashboardImage->image1) Storage::disk('public')->delete($dashboardImage->image1);
        if ($dashboardImage->image2) Storage::disk('public')->delete($dashboardImage->image2);

        $dashboardImage->delete();

        return redirect()->route('admin.dashboard-images.index')->with('success', 'Images deleted successfully.');
    }
}
