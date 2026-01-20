<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MatchHighlight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MatchHighlightController extends Controller
{
    public function index()
    {
        $matches = MatchHighlight::latest()->get();
        return view('admin.matches.index', compact('matches'));
    }

    public function create()
    {
        return view('admin.matches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|in:all,match_highlights,press_release,media,blogs',
            'thumbnail' => 'required|image|mimes:jpg,jpeg,png,webp',
            'video_title' => 'nullable|string',
            'video_url' => 'nullable|url',

            'images.*' => 'required|image|mimes:jpg,jpeg,png,webp',
            'contents.*.heading' => 'nullable|string',
            'contents.*.content' => 'required|string',
        ]);

        /** 1️⃣ Store thumbnail */
        $thumbnailPath = $request->file('thumbnail')
            ->store('matches/thumbnail', 'public');

        /** 2️⃣ Create main record */
        $match = MatchHighlight::create([
            'title' => $request->title,
            'description' => $request->description,
            'thumbnail' => $thumbnailPath,
            'type' => $request->type,
            'video_title' => $request->video_title,
            'video_url' => $request->video_url,
        ]);

        /** 3️⃣ Store gallery images */
        foreach ($request->file('images') as $i => $image) {
            $match->images()->create([
                'image' => $image->store('matches/gallery', 'public'),
                'sort_order' => $i
            ]);
        }

        /** 4️⃣ Store content blocks */
        foreach ($request->contents as $i => $block) {
            $match->contents()->create([
                'heading' => $block['heading'] ?? null,
                'content' => $block['content'],
                'sort_order' => $i
            ]);
        }

        return redirect()
            ->route('admin.matches.index')
            ->with('success', 'Match highlight created successfully.');
    }



    public function edit(MatchHighlight $match)
    {
        return view('admin.matches.edit', compact('match'));
    }

    public function update(Request $request, MatchHighlight $match)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'type' => 'required|in:all,match_highlights,press_release,media,blogs',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'video_title' => 'nullable|string',
            'video_url' => 'nullable|url',

            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'contents.*.heading' => 'nullable|string',
            'contents.*.content' => 'required|string',
            'remove_images' => 'array'
        ]);

        /** 1️⃣ Replace thumbnail if uploaded */
        if ($request->hasFile('thumbnail')) {
            if ($match->thumbnail && Storage::disk('public')->exists($match->thumbnail)) {
                Storage::disk('public')->delete($match->thumbnail);
            }

            $match->thumbnail = $request->file('thumbnail')
                ->store('matches/thumbnail', 'public');
        }

        /** 2️⃣ Update main record */
        $match->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'video_title' => $request->video_title,
            'video_url' => $request->video_url,
            'thumbnail' => $match->thumbnail
        ]);

        /** 3️⃣ Remove selected gallery images */
        if ($request->filled('remove_images')) {
            foreach ($match->images()->whereIn('id', $request->remove_images)->get() as $img) {
                Storage::disk('public')->delete($img->image);
                $img->delete();
            }
        }

        /** 4️⃣ Add new gallery images */
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $image) {
                $match->images()->create([
                    'image' => $image->store('matches/gallery', 'public'),
                    'sort_order' => $i
                ]);
            }
        }

        /** 5️⃣ Replace content blocks */
        $match->contents()->delete();
        
        if ($request->has('contents') && is_array($request->contents)) {
            foreach ($request->contents as $i => $block) {
                $match->contents()->create([
                    'heading' => $block['heading'] ?? null,
                    'content' => $block['content'],
                    'sort_order' => $i
                ]);
            }
        }


        return redirect()
            ->route('admin.matches.index')
            ->with('success', 'Match highlight updated successfully.');
    }


    public function destroy(MatchHighlight $match)
    {
        if ($match->thumbnail) {
            Storage::disk('public')->delete($match->thumbnail);
        }

        foreach ($match->images as $img) {
            Storage::disk('public')->delete($img->image);
        }

        $match->delete();

        return back()->with('success', 'Match highlight deleted successfully.');
    }
}
