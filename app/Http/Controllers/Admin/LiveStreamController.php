<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\LiveStream;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LiveStreamController extends Controller
{
    public function index()
    {
        $liveStreams = LiveStream::with('game', 'tournament')
            ->latest('started_at')
            ->paginate(10);

        // Global counts (not just current page)
        $liveNowCount   = LiveStream::whereNotNull('started_at')
                                    ->where('started_at', '<=', now())
                                    ->count();

        $upcomingCount  = LiveStream::where(function ($q) {
                                $q->whereNull('started_at')
                                  ->orWhere('started_at', '>', now());
                            })->count();

        $platformsCount = LiveStream::distinct('platform')->count('platform');

        return view('admin.livestream.index', compact(
            'liveStreams',
            'liveNowCount',
            'upcomingCount',
            'platformsCount'
        ));
    }

    public function create()
    {
        $games = Game::all();
        return view('admin.livestream.create', compact('games'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tournament_id' => 'nullable|exists:tournaments,id',
            'game_id'       => 'required|exists:games,id',
            'platform'      => 'required|string|max:100',
            'channel_name'  => 'required|string|max:255',
            'language'      => 'required|string|max:50',
            'video_url'     => 'required|url',
            'viewer_count'  => 'nullable|integer',
            'started_at'    => 'required|date',
        ]);

        $data = $request->except('is_live');

        // Auto compute is_live from started_at
        $data['is_live']        = Carbon::parse($request->started_at)->lte(now()) ? 1 : 0;
        $data['last_synced_at'] = now();

        LiveStream::create($data);

        return redirect()->route('admin.livestream.index')
            ->with('success', 'Live Stream added successfully.');
    }

    public function edit($id)
    {
        $liveStream  = LiveStream::findOrFail($id);
        $games       = Game::all();
        $tournaments = Tournament::where('game_id', $liveStream->game_id)->get();

        return view('admin.livestream.edit', compact('liveStream', 'games', 'tournaments'));
    }

    public function update(Request $request, $id)
    {
        $liveStream = LiveStream::findOrFail($id);

        $request->validate([
            'tournament_id' => 'nullable|exists:tournaments,id',
            'game_id'       => 'required|exists:games,id',
            'platform'      => 'required|string|max:100',
            'channel_name'  => 'required|string|max:255',
            'language'      => 'required|string|max:50',
            'video_url'     => 'required|url',
            'viewer_count'  => 'nullable|integer',
            'started_at'    => 'required|date',
        ]);

        $data = $request->except('is_live');

        // Auto compute is_live from started_at
        $data['is_live']        = Carbon::parse($request->started_at)->lte(now()) ? 1 : 0;
        $data['last_synced_at'] = now();

        $liveStream->update($data);

        return redirect()->route('admin.livestream.index')
            ->with('success', 'Live Stream updated successfully.');
    }

    public function destroy($id)
    {
        $liveStream = LiveStream::findOrFail($id);
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