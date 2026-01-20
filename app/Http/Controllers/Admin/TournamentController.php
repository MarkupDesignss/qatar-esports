<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class TournamentController extends Controller
{
    // public function index()
    // {
    //     $tournaments = Tournament::with('game')
    //         ->latest()
    //         ->paginate(10);

    //     $stats = [
    //         'total'     => Tournament::count(),
    //         'live'      => Tournament::where('status', 'live')->count(),
    //         'upcoming'  => Tournament::where('status', 'upcoming')->count(),
    //         'featured'  => Tournament::where('is_featured', 1)->count(),
    //     ];

    //     return view('admin.tournaments.index', compact('tournaments', 'stats'));
    // }

    public function index()
    {
        $now = Carbon::now();

        $tournaments = Tournament::with('game')->latest()->paginate(10);

        $stats = [
            'total' => Tournament::count(),

            'upcoming' => Tournament::whereDate('start_date', '>', $now)->count(),

            'live' => Tournament::whereDate('start_date', '<=', $now)
                ->where(function ($q) use ($now) {
                    $q->whereNull('end_date')
                      ->orWhereDate('end_date', '>=', $now);
                })
                ->count(),

            'featured' => Tournament::where('is_featured', 1)->count(),
        ];

        return view('admin.tournaments.index', compact('tournaments', 'stats'));
    }


    public function create()
    {
        $games = Game::orderBy('name')->get();
        return view('admin.tournaments.create', compact('games'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'game_id'              => 'required|exists:games,id',
            'title'                => 'required|string|max:191',
            'slug'                 => 'nullable|unique:tournaments,slug',
            'logo'                 => 'nullable|image|max:2048',
            'banner'               => 'nullable|image|max:4096',
            'location'             => 'nullable|string|max:191',
            'format'               => 'required|in:solo,team',
            'team_size'            => 'nullable|integer|min:1',
            'visibility'           => 'required|in:draft,published,archived',
            'is_featured'          => 'boolean',
            //'is_registration_open' => 'boolean',
            'registration_start'   => 'required|date',
            'registration_end'     => 'required|date|after_or_equal:registration_start',
            'start_date'           => 'required|date',
            'start_time'           => 'required|date_format:H:i',
            'end_date'             => 'required|date|after_or_equal:start_date',
            //'timezone'             => 'nullable|string|max:191',
            'entry_fee'            => 'required|numeric|min:0',
            'prize_pool'           => 'required|numeric|min:0',
            'max_participants'     => 'nullable|integer|min:1',
            'description'          => 'nullable|string',
            'rules'                => 'nullable|string',
        ]);

        /* ---------- Slug Handling ---------- */
        if (empty($data['slug'])) {
            $slug = Str::slug($data['title']);
            $count = Tournament::where('slug', 'like', "{$slug}%")->count();
            $data['slug'] = $count ? "{$slug}-" . ($count + 1) : $slug;
        }

        /* ---------- Checkbox Defaults ---------- */
        $data['is_featured'] = $request->boolean('is_featured');
        //$data['is_registration_open'] = $request->boolean('is_registration_open');

        /* ---------- Status Auto Calculation ---------- */
        // $now = Carbon::now();

        // if ($data['start_date']) {
        //     $start = Carbon::parse($data['start_date']);
        //     $end   = $data['end_date'] ? Carbon::parse($data['end_date']) : null;

        //     if ($start->isFuture()) {
        //         $data['status'] = 'upcoming';
        //     } elseif ($end && $end->isPast()) {
        //         $data['status'] = 'completed';
        //     } else {
        //         $data['status'] = 'live';
        //     }
        // } else {
        //     $data['status'] = 'upcoming';
        // }

        $data['created_by'] = auth()->id();

        /* ---------- File Uploads ---------- */
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('tournaments', 'public');
        }

        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')->store('tournaments', 'public');
        }

        Tournament::create($data);

        return redirect()
            ->route('admin.tournaments.index')
            ->with('success', 'Tournament created successfully.');
    }

    public function edit(Tournament $tournament)
    {
        $games = Game::orderBy('name')->get();
        return view('admin.tournaments.edit', compact('tournament', 'games'));
    }

    public function update(Request $request, Tournament $tournament)
    {
        $data = $request->validate([
            'game_id'              => 'nullable|exists:games,id',
            'title'                => 'required|string|max:191',
            'slug'                 => 'nullable|unique:tournaments,slug,' . $tournament->id,
            'logo'                 => 'nullable|image|max:2048',
            'banner'               => 'nullable|image|max:4096',
            'location'             => 'nullable|string|max:191',
            'format'               => 'nullable|in:solo,team',
            'team_size'            => 'nullable|integer',
            //'status'               => 'required|in:upcoming,live,completed',
            'visibility'           => 'required|in:draft,published,archived',
            'is_featured'          => 'sometimes|boolean',
            //'is_registration_open' => 'sometimes|boolean',
            'registration_start'   => 'nullable|date',
            'registration_end'     => 'nullable|date|after_or_equal:registration_start',
            'start_date'           => 'nullable|date',
            'end_date'             => 'nullable|date|after_or_equal:start_date',
            'start_time'           => 'nullable',
            //'timezone'             => 'nullable|string|max:191',
            'entry_fee'            => 'nullable|numeric|min:0',
            'prize_pool'           => 'nullable|numeric|min:0',
            'max_participants'     => 'nullable|integer|min:1',
            'description'          => 'nullable|string',
            'rules'                => 'nullable|string',
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);

        // Checkbox fix: agar unchecked ho to false save kare
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
        //$data['is_registration_open'] = $request->has('is_registration_open') ? 1 : 0;

        // File handling
        if ($request->hasFile('logo')) {
            if ($tournament->logo) {
                Storage::disk('public')->delete($tournament->logo);
            }
            $data['logo'] = $request->file('logo')->store('tournaments', 'public');
        }

        if ($request->hasFile('banner')) {
            if ($tournament->banner) {
                Storage::disk('public')->delete($tournament->banner);
            }
            $data['banner'] = $request->file('banner')->store('tournaments', 'public');
        }

        // Optional: status auto-update based on date/time
        // if ($data['start_date'] && $data['end_date']) {
        //     $now = now();
        //     $startDateTime = $data['start_date'] . ' ' . ($data['start_time'] ?? '00:00:00');
        //     $endDateTime = $data['end_date'] . ' 23:59:59';

        //     if ($now->lt($startDateTime)) {
        //         $data['status'] = 'upcoming';
        //     } elseif ($now->between($startDateTime, $endDateTime)) {
        //         $data['status'] = 'live';
        //     } else {
        //         $data['status'] = 'completed';
        //     }
        // }

        $tournament->update($data);

        return redirect()
            ->route('admin.tournaments.index')
            ->with('success', 'Tournament updated successfully.');
    }


    public function show(Tournament $tournament)
    {
        return view('admin.tournaments.show', compact('tournament'));
    }


    public function toggleFeatured($id)
    {
        $tournament = Tournament::findOrFail($id);

        $tournament->update([
            'is_featured' => !$tournament->is_featured
        ]);

        return back()->with('success', 'Featured status updated.');
    }

    public function toggleVisibility($id)
    {
        $tournament = Tournament::findOrFail($id);

        $tournament->update([
            'visibility' => $tournament->visibility === 'published' ? 'draft' : 'published'
        ]);

        return back()->with('success', 'Visibility updated.');
    }
}
