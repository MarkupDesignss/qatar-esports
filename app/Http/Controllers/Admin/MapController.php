<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Map;
use App\Models\Game;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MapController extends Controller
{
    public function index()
    {
        $maps = Map::with('game')->latest()->paginate(10);
        return view('admin.maps.index', compact('maps'));
    }


    public function create()
    {
        $games = Game::pluck('name', 'id');
        return view('admin.maps.create', compact('games'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image'
        ]);

        $image = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('maps', 'public');
        }

        Map::create([
            'game_id' => $request->game_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $image,
            'is_active' => $request->is_active ?? 1
        ]);

        return redirect()->route('admin.maps.index')->with('success', 'Map Created');
    }


    public function edit($id)
    {
        $map = Map::findOrFail($id);
        $games = Game::pluck('name', 'id');

        return view('admin.maps.edit', compact('map', 'games'));
    }

    public function update(Request $request, $id)
    {
        $map = Map::findOrFail($id);

        $request->validate([
            'game_id' => 'required|exists:games,id',
            'name' => 'required',
            'image' => 'nullable|image'
        ]);

        if ($request->hasFile('image')) {

            // delete previous image
            if ($map->image && Storage::disk('public')->exists($map->image)) {
                Storage::disk('public')->delete($map->image);
            }

            // upload new image
            $image = $request->file('image')->store('maps', 'public');

            $map->image = $image;
        }

        $map->update([
            'game_id' => $request->game_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'is_active' => $request->is_active ?? 1
        ]);

        $map->save();

        return redirect()->route('admin.maps.index')->with('success', 'Map Updated');
    }


    public function destroy($id)
    {
        Map::findOrFail($id)->delete();

        return back()->with('success', 'Map Deleted');
    }
}
