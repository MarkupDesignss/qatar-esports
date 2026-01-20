<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\LiveStream;
use App\Models\Tournament;
use Illuminate\Http\Request;

class LiveStreamController extends Controller
{
    public function index()
    {
        $liveStreams = LiveStream::with('game', 'tournament')->latest()->paginate(10);
        return view('admin.livestream.index', compact('liveStreams'));
    }

    public function create()
    {
        $games = Game::all();
        return view('admin.livestream.create', compact('games'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'game_id'       => 'required|exists:games,id',
            'platform'      => 'required|string|max:100',
            'channel_name'  => 'required|string|max:255',
            'language'      => 'required|string|max:50',
            'video_url'     => 'required|url',
            'is_live'       => 'required|boolean',
            'viewer_count'  => 'nullable|integer',
            'started_at'    => 'nullable|date',
            'last_synced_at' => 'nullable|date',
        ]);

        LiveStream::create($request->all());

        return redirect()->route('admin.livestream.index')
            ->with('success', 'Live Stream added successfully.');
    }

    public function edit($id)
    {
        $liveStream = LiveStream::find($id);
        $games = Game::all();
        $tournaments = Tournament::where('game_id', $liveStream->game_id)->get();
        return view('admin.livestream.edit', compact('liveStream', 'games', 'tournaments'));
    }

    public function update(Request $request, $id)
    {
        $liveStream = LiveStream::find($id);
        $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'game_id'       => 'required|exists:games,id',
            'platform'      => 'required|string|max:100',
            'channel_name'  => 'required|string|max:255',
            'language'      => 'required|string|max:50',
            'video_url'     => 'required|url',
            'is_live'       => 'required|boolean',
            'viewer_count'  => 'nullable|integer',
            'started_at'    => 'nullable|date',
            'last_synced_at' => 'nullable|date',
        ]);

        $liveStream->update($request->all());

        return redirect()->route('admin.livestream.index')
            ->with('success', 'Live Stream updated successfully.');
    }

    public function destroy($id)
    {
        $liveStream = LiveStream::find($id);
        $liveStream->delete();
        return redirect()->route('admin.livestream.index')
            ->with('success', 'Live Stream deleted successfully.');
    }

    // Ajax method to fetch tournaments based on selected game
    public function getTournaments(Request $request)
    {
        $tournaments = Tournament::where('game_id', $request->game_id)->get();
        return response()->json($tournaments);
    }
}