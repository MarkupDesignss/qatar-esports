<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::latest()->paginate(10);
        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        return view('admin.games.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'slug'     => 'nullable|unique:games,slug',
            'platform' => 'required|in:PC,Mobile,Console',
            'logo'     => 'nullable|image|max:2048',
            'banner'   => 'nullable|image|max:4096',
            'status'   => 'required|boolean',
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('games', 'public');
        }

        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')->store('games', 'public');
        }

        Game::create($data);

        return redirect()
            ->route('admin.games.index')
            ->with('success', 'Game created successfully.');
    }

    public function edit(Game $game)
    {
        return view('admin.games.edit', compact('game'));
    }

    public function update(Request $request, Game $game)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'slug'     => 'nullable|unique:games,slug,' . $game->id,
            'platform' => 'required|in:PC,Mobile,Console',
            'logo'     => 'nullable|image|max:2048',
            'banner'   => 'nullable|image|max:4096',
            'status'   => 'required|boolean',
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        if ($request->hasFile('logo')) {
            if ($game->logo) {
                Storage::disk('public')->delete($game->logo);
            }
            $data['logo'] = $request->file('logo')->store('games', 'public');
        }

        if ($request->hasFile('banner')) {
            if ($game->banner) {
                Storage::disk('public')->delete($game->banner);
            }
            $data['banner'] = $request->file('banner')->store('games', 'public');
        }

        $game->update($data);

        return redirect()
            ->route('admin.games.index')
            ->with('success', 'Game updated successfully.');
    }

    public function toggleStatus($id)
    {
        $game = Game::findOrFail($id);

        $game->update([
            'status' => !$game->status
        ]);

        return back()->with('success', 'Status updated.');
    }
}