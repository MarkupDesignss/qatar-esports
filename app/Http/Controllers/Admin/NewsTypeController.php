<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsTypeController extends Controller
{
    /**
     * Display a listing of news types.
     */
    public function index()
    {
        $types = NewsType::orderBy('sort_order')->paginate(10);
        return view('admin.news_types.index', compact('types'));
    }

    /**
     * Show the form for creating a new news type.
     */
    public function create()
    {
        return view('admin.news_types.create');
    }

    /**
     * Store a newly created news type in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255|unique:news_types,name',
            'is_active'  => 'sometimes|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        // Auto-generate slug from name
        $slug = Str::slug($validated['name'], '_'); // "All News" -> "all_news"

        // Ensure slug is unique
        $originalSlug = $slug;
        $counter = 1;
        while (NewsType::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '_' . $counter;
            $counter++;
        }

        $validated['slug'] = $slug;
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['sort_order'] = $validated['sort_order'] ?? (NewsType::max('sort_order') + 1);

        NewsType::create($validated);

        return redirect()->route('admin.news-types.index')
            ->with('success', 'News type created successfully.');
    }

    /**
     * Show the form for editing the specified news type.
     */
    public function edit(NewsType $newsType)
    {
        return view('admin.news_types.edit', compact('newsType'));
    }

    /**
     * Update the specified news type in storage.
     */
    public function update(Request $request, NewsType $newsType)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255|unique:news_types,name,' . $newsType->id,
            'is_active'  => 'sometimes|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        // Slug intentionally NOT changed – purana slug maintain rahega
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $newsType->update($validated);

        return redirect()->route('admin.news-types.index')
            ->with('success', 'News type updated successfully.');
    }

    /**
     * Remove the specified news type from storage.
     */
    public function destroy(NewsType $newsType)
    {
        // Prevent deletion if it has related news (optional)
        if ($newsType->news()->exists()) {
            return redirect()->route('admin.news-types.index')
                ->with('error', 'Cannot delete type because it is used by existing news. Please reassign or delete those news first.');
        }

        $newsType->delete();

        return redirect()->route('admin.news-types.index')
            ->with('success', 'News type deleted successfully.');
    }
}