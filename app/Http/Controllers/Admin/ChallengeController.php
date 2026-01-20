<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChallengeController extends Controller
{
    public function index()
    {
        $challenges = Challenge::latest()->get();
        return view('admin.challenge.index', compact('challenges'));
    }

    public function create()
    {
        return view('admin.challenge.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'heading'   => 'required|string|max:255',
            'content'   => 'required|string',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'video_url' => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('challenges', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('challenges/thumbnails', 'public');
        }

        Challenge::create($data);

        return redirect()
            ->route('admin.challenge.index')
            ->with('success', 'Challenge created successfully.');
    }

    public function edit($id)
    {
        $challenge = Challenge::findOrFail($id);
        return view('admin.challenge.edit', compact('challenge'));
    }

    public function update(Request $request, $id)
    {
        $challenge = Challenge::findOrFail($id);

        $data = $request->validate([
            'heading'   => 'required|string|max:255',
            'content'   => 'required|string',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'video_url' => 'nullable|url',
        ]);

        // Image update
        if ($request->hasFile('image')) {
            if ($challenge->image && Storage::disk('public')->exists($challenge->image)) {
                Storage::disk('public')->delete($challenge->image);
            }

            $data['image'] = $request->file('image')->store('challenges', 'public');
        }

        // Thumbnail update
        if ($request->hasFile('thumbnail')) {
            if ($challenge->thumbnail && Storage::disk('public')->exists($challenge->thumbnail)) {
                Storage::disk('public')->delete($challenge->thumbnail);
            }

            $data['thumbnail'] = $request->file('thumbnail')->store('challenges/thumbnails', 'public');
        }

        $challenge->update($data);

        return redirect()
            ->route('admin.challenge.index')
            ->with('success', 'Challenge updated successfully.');
    }

    public function destroy($id)
    {
        $challenge = Challenge::findOrFail($id);

        if ($challenge->image && Storage::disk('public')->exists($challenge->image)) {
            Storage::disk('public')->delete($challenge->image);
        }

        if ($challenge->thumbnail && Storage::disk('public')->exists($challenge->thumbnail)) {
            Storage::disk('public')->delete($challenge->thumbnail);
        }

        $challenge->delete();

        return redirect()
            ->route('admin.challenge.index')
            ->with('success', 'Challenge deleted successfully.');
    }
}
