<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Tournament;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with('tournament')->latest()->get();
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        $tournaments = Tournament::pluck('title', 'id');
        return view('admin.news.create', compact('tournaments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tournament_id' => 'nullable|exists:tournaments,id',
            'title' => 'required',
            'description' => 'required',
            'type' => 'required',
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
        return view('admin.news.edit', compact('news', 'tournaments'));
    }

    public function update(Request $request, $id)
    {
        $news = News::find($id);
        $data = $request->validate([
            'tournament_id' => 'nullable',
            'title' => 'required',
            'description' => 'required',
            'type' => 'required'
        ]);

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
