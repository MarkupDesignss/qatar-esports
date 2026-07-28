<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\TournamentRegistration;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class TournamentController extends Controller
{

    public function index(Request $request)
    {
        $now = Carbon::now();

        // Fetch filter inputs
        $format = $request->input('format'); // 'solo' or 'team'
        $status = $request->input('status'); // 'upcoming', 'live', 'past' (optional)
        // date range filters (accepts date_from/date_to or start_date/end_date)
        $dateFrom = $request->input('date_from') ?? $request->input('start_date');
        $dateTo = $request->input('date_to') ?? $request->input('end_date');

        // Build base query for listing
        $listQuery = Tournament::with('game');

        if (!empty($format)) {
            $listQuery->where('format', $format);
        }

        if (!empty($status)) {
            if ($status === 'upcoming') {
                $listQuery->where('start_date', '>', $now);
            } elseif ($status === 'live') {
                $listQuery->where('start_date', '<=', $now)->where('end_date', '>=', $now);
            } elseif ($status === 'past' || $status === 'finished') {
                $listQuery->where('end_date', '<', $now);
            }
        }

        if (!empty($dateFrom)) {
            try {
                $from = Carbon::parse($dateFrom)->startOfDay();
                $listQuery->where('start_date', '>=', $from);
            } catch (\Exception $e) {
                // ignore invalid date
            }
        }

        if (!empty($dateTo)) {
            try {
                $to = Carbon::parse($dateTo)->endOfDay();
                $listQuery->where('end_date', '<=', $to);
            } catch (\Exception $e) {
                // ignore invalid date
            }
        }

        $tournaments = $listQuery->latest()->paginate(10)->appends($request->except('page'));

        // Build a separate base query for stats with same filters
        $statsBase = Tournament::query();
        if (!empty($format)) {
            $statsBase->where('format', $format);
        }
        if (!empty($status)) {
            if ($status === 'upcoming') {
                $statsBase->where('start_date', '>', $now);
            } elseif ($status === 'live') {
                $statsBase->where('start_date', '<=', $now)->where('end_date', '>=', $now);
            } elseif ($status === 'past' || $status === 'finished') {
                $statsBase->where('end_date', '<', $now);
            }
        }
        if (!empty($dateFrom)) {
            try {
                $from = Carbon::parse($dateFrom)->startOfDay();
                $statsBase->where('start_date', '>=', $from);
            } catch (\Exception $e) {
            }
        }
        if (!empty($dateTo)) {
            try {
                $to = Carbon::parse($dateTo)->endOfDay();
                $statsBase->where('end_date', '<=', $to);
            } catch (\Exception $e) {
            }
        }

        $stats = [
            'total' => (clone $statsBase)->count(),
            'upcoming' => (clone $statsBase)->where('start_date', '>', $now)->count(),
            'live' => (clone $statsBase)->where('start_date', '<=', $now)->where('end_date', '>=', $now)->count(),
            'featured' => (clone $statsBase)->where('is_featured', 1)->count(),
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
            'logo'                 => 'nullable|image',
            'banner'               => 'nullable|image|max:4096',
            'location'             => 'nullable|string|max:191',
            'format'               => 'required|in:solo,team',
            'team_size'            => 'nullable|integer|min:1',
            'visibility'           => 'required|in:draft,published,archived',
            'is_featured'          => 'boolean',
            'allow_pdf_download'   => 'boolean',

            // datetime-local fields
            'registration_start'   => 'required|date',
            'registration_end'     => 'required|date|after:registration_start',
            'start_date'           => 'required|date',
            'end_date'             => 'required|date|after:start_date',

            'entry_fee'            => 'required|numeric|min:0',
            'prize_pool'           => 'required|numeric|min:0',
            'max_participants'     => 'nullable|integer|min:1',
            'description'          => 'nullable|string',
            'rules'                => 'nullable|string',
            'social_links'         => 'nullable|array',
            'social_links.*'       => 'nullable|url|max:255',
            'stream_url'           => 'nullable|url|max:255',
        ]);

        /* ---------- Slug Handling ---------- */
        if (empty($data['slug'])) {
            $slug = Str::slug($data['title']);
            $count = Tournament::where('slug', 'like', "{$slug}%")->count();
            $data['slug'] = $count ? "{$slug}-" . ($count + 1) : $slug;
        }

        /* ---------- Checkbox ---------- */
        $data['is_featured'] = $request->boolean('is_featured');
        $data['allow_pdf_download'] = $request->boolean('allow_pdf_download');

        /* ---------- Datetime Handling (IMPORTANT) ---------- */
        $data['registration_start'] = Carbon::parse($data['registration_start']);
        $data['registration_end']   = Carbon::parse($data['registration_end']);
        $data['start_date']         = Carbon::parse($data['start_date']);
        $data['end_date']           = Carbon::parse($data['end_date']);

        $data['created_by'] = auth()->id();

        /* ---------- File Uploads ---------- */
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('tournaments', 'public');
        }

        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')->store('tournaments', 'public');
        }

        $socialLinks = $request->input('social_links', []);
        $data['social_links'] = array_filter($socialLinks, function ($value) {
            return !empty($value);  // removes null, '', false, 0, etc.
        });

        // If you want to keep only specific platforms (optional):
        // $allowedPlatforms = ['youtube', 'twitch', 'instagram', 'facebook', 'discord', 'tiktok', 'twitter'];
        // $data['social_links'] = array_intersect_key($data['social_links'], array_flip($allowedPlatforms));

        // If no links remain, set to null (so JSON column stores NULL)
        $data['social_links'] = empty($data['social_links']) ? null : $data['social_links'];

        // Store stream_url as-is
        $data['stream_url'] = $request->input('stream_url');

        /* ---------- Save ---------- */
        Tournament::create($data);

        return redirect()
            ->route('admin.tournaments.index')
            ->with('success', 'Tournament created successfully.');
    }


    // public function edit(Tournament $tournament)
    // {
    //     $games = Game::orderBy('name')->get();
    //     return view('admin.tournaments.edit', compact('tournament', 'games'));
    // }
    public function edit(Tournament $tournament)
    {
        // Prevent editing after registration has closed
        // if (
        //     !$tournament->is_registration_open ||
        //     now()->greaterThan($tournament->registration_end)
        // ) {
        //     return redirect()
        //         ->route('admin.tournaments.index')
        //         ->with('error', 'Tournament cannot be updated after registration has closed.');
        // }

        $games = Game::orderBy('name')->get();

        return view('admin.tournaments.edit', compact('tournament', 'games'));
    }

    public function update(Request $request, Tournament $tournament)
    {
        $data = $request->validate([
            'game_id'              => 'nullable|exists:games,id',
            'title'                => 'required|string|max:191',
            'slug'                 => 'nullable|unique:tournaments,slug,' . $tournament->id,
            'logo'                 => 'nullable|image',
            'banner'               => 'nullable|image|max:4096',
            'location'             => 'nullable|string|max:191',
            'format'               => 'nullable|in:solo,team',
            'team_size'            => 'nullable|integer|min:1',
            'visibility'           => 'required|in:draft,published,archived',
            'is_featured'          => 'sometimes|boolean',
            'allow_pdf_download'   => 'sometimes|boolean',

            // datetime fields
            'registration_start'   => 'nullable|date',
            'registration_end'     => 'nullable|date|after:registration_start',
            'start_date'           => 'nullable|date',
            'end_date'             => 'nullable|date|after:start_date',

            'entry_fee'            => 'nullable|numeric|min:0',
            'prize_pool'           => 'nullable|numeric|min:0',
            'max_participants'     => 'nullable|integer|min:1',
            'description'          => 'nullable|string',
            'rules'                => 'nullable|string',
            'social_links'         => 'nullable|array',
            'social_links.*'       => 'nullable|url|max:255',
            'stream_url'           => 'nullable|url|max:255',
        ]);

        /* ---------- Slug ---------- */
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);

        /* ---------- Checkbox ---------- */
        $data['is_featured'] = $request->boolean('is_featured');
        $data['allow_pdf_download'] = $request->boolean('allow_pdf_download');

        /* ---------- Datetime Handling ---------- */
        if (!empty($data['registration_start'])) {
            $data['registration_start'] = Carbon::parse($data['registration_start']);
        }

        if (!empty($data['registration_end'])) {
            $data['registration_end'] = Carbon::parse($data['registration_end']);
        }

        if (!empty($data['start_date'])) {
            $data['start_date'] = Carbon::parse($data['start_date']);
        }

        if (!empty($data['end_date'])) {
            $data['end_date'] = Carbon::parse($data['end_date']);
        }

        /* ---------- File Handling ---------- */
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

    public function exportParticipants($id)
    {
        $tournament = Tournament::findOrFail($id);

        // Fetch all registrations for this tournament with user details
        $registrations = TournamentRegistration::with('user')
            ->where('tournament_id', $id)
            ->get();

        if ($registrations->isEmpty()) {
            return back()->with('error', 'No participants found for this tournament.');
        }

        // Prepare CSV headers
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $tournament->title . '_participants_' . date('Y-m-d') . '.csv"',
        ];

        // Prepare CSV columns
        $columns = [
            'Registration ID',
            'Participant Name',
            'Email',
            'Phone',
            'Type',
            'Team Name',
            'Team Tag',
            'Status',
            'Registered At'
        ];

        // Callback to generate CSV
        $callback = function () use ($registrations, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($registrations as $reg) {
                $user = $reg->user;

                fputcsv($file, [
                    $reg->id,
                    $user ? trim($user->first_name . ' ' . $user->last_name) : $reg->name,
                    $user ? $user->email : $reg->email,
                    $user ? $user->mobile : $reg->phone,
                    ucfirst($reg->type),
                    $reg->team_name ?? 'N/A',
                    $reg->team_tag ?? 'N/A',
                    $reg->status == 1 ? 'Active' : 'Inactive',
                    $reg->created_at ? $reg->created_at->format('d M Y, h:i A') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
