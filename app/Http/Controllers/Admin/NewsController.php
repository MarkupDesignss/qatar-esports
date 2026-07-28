<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Tournament;
use App\Models\NewsType;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with('tournament', 'type')->latest()->paginate(10);
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        $tournaments = Tournament::pluck('title', 'id');
        $types = NewsType::orderBy('sort_order')->get();
        return view('admin.news.create', compact('tournaments', 'types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tournament_id' => 'nullable|exists:tournaments,id',
            'title' => 'required',
            'description' => 'required',
            'type_id'       => 'required|exists:news_types,id',
            'thumbnail' => 'nullable|image'
        ]);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('news', 'public');
        }

        News::create($data);
        return redirect()->route('admin.news.index');
    }

    public function edit($id)
    {
        $news = News::find($id);
        $tournaments = Tournament::pluck('title', 'id');
        $types = NewsType::orderBy('sort_order')->get();
        return view('admin.news.edit', compact('news', 'tournaments', 'types'));
    }

    // public function update(Request $request, $id)
    // {
    //     $news = News::find($id);
    //     $data = $request->validate([
    //         'tournament_id' => 'nullable',
    //         'title' => 'required',
    //         'description' => 'required',
    //         'type_id'       => 'required|exists:news_types,id',
    //     ]);
        
    //     $news->update($data);
    //     return redirect()->route('admin.news.index');
    // }
    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);
    
        $data = $request->validate([
            'tournament_id' => 'nullable|exists:tournaments,id',
            'title'         => 'required',
            'description'   => 'required',
            'type_id'       => 'required|exists:news_types,id',
            'thumbnail'     => 'nullable|image',
        ]);
    
        if ($request->hasFile('thumbnail')) {
    
            // Delete old thumbnail
            if ($news->thumbnail && Storage::disk('public')->exists($news->thumbnail)) {
                Storage::disk('public')->delete($news->thumbnail);
            }
    
            // Upload new thumbnail
            $data['thumbnail'] = $request->file('thumbnail')->store('news', 'public');
        }
    
        $news->update($data);
    
        return redirect()->route('admin.news.index');
    }

    public function destroy($id)
    {
        $news = News::find($id);
        $news->delete();
        return back();
    }
}
