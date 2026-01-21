<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tournament;
use Illuminate\Support\Facades\DB;
use App\Models\TournamentRegistration;
use Carbon\Carbon;

class TournamentController extends Controller
{
    /**
     * List tournaments
     * GET /api/tournaments
     * Query params: search, game, filter (e.g., NearMe), status
     */

public function index(Request $request)
    {
        $now  = now();
        $user = $request->user();

        /*
    |-------------------------------------------------
    | Base Query
    |-------------------------------------------------
    */
        $queryBuilder = Tournament::with('game')
            ->where('visibility', 'published')
            ->orderByDesc('is_featured')
            ->orderBy('start_date', 'asc');

        /*
    |-------------------------------------------------
    | Search by Title
    |-------------------------------------------------
    */
        if ($request->filled('query')) {
            $queryBuilder->where('title', 'like', '%' . $request->query('query') . '%');
        }

        /*
    |-------------------------------------------------
    | Filter Logic
    | - live / upcoming / completed (DATE BASED)
    | - nearby
    | - game name (fallback)
    | - all
    |-------------------------------------------------
    */
        if ($request->filled('filter')) {

            $filter = strtolower($request->filter);

            if (in_array($filter, ['upcoming', 'live', 'completed'])) {

                if ($filter === 'upcoming') {
                    $queryBuilder->where(function ($q) use ($now) {
                        $q->whereDate('start_date', '>', $now->toDateString());
                    });
                } elseif ($filter === 'live') {
                    $queryBuilder->where(function ($q) use ($now) {
                        $q->whereDate('start_date', '<=', $now->toDateString())
                            ->whereDate('end_date', '>=', $now->toDateString());
                    });
                } elseif ($filter === 'completed') {
                    $queryBuilder->where(function ($q) use ($now) {
                        $q->whereDate('end_date', '<', $now->toDateString());
                    });
                }
            } elseif ($filter === 'nearby') {

                if ($user && $user->location) {
                    $queryBuilder->where('location', 'like', '%' . $user->location . '%');
                }
            } elseif ($filter !== 'all') {

                // 👇 GAME NAME FILTER (fallback)
                $queryBuilder->whereHas('game', function ($q) use ($filter) {
                    $q->where('name', 'like', '%' . $filter . '%');
                });
            }
        }

        /*
    |-------------------------------------------------
    | Fetch Data
    |-------------------------------------------------
    */
        $tournaments = $queryBuilder->get();

        /*
    |-------------------------------------------------
    | Transform Response
    |-------------------------------------------------
    */
        $response = $tournaments->map(function ($t) use ($now) {

            // Start datetime
            $start = null;
            if ($t->start_date) {
                if (strlen($t->start_date) > 10) {
                    $start = Carbon::parse($t->start_date);
                } else {
                    $start = Carbon::parse(
                        $t->start_date . ' ' . ($t->start_time ?? '00:00:00')
                    );
                }
            }

            // End datetime
            $end = $t->end_date ? Carbon::parse($t->end_date)->endOfDay() : null;

            // Dynamic status
            if ($start && $end) {
                if ($now->lt($start)) {
                    $status_dynamic = 'upcoming';
                } elseif ($now->between($start, $end)) {
                    $status_dynamic = 'live';
                } else {
                    $status_dynamic = 'completed';
                }
            } else {
                $status_dynamic = $t->status;
            }

            // Registration open logic
            $isRegistrationOpen = false;
            if ($t->registration_start || $t->registration_end) {
                $isRegistrationOpen = $now->between(
                    $t->registration_start ? Carbon::parse($t->registration_start)->startOfDay() : $now->copy()->subYears(5),
                    $t->registration_end ? Carbon::parse($t->registration_end)->endOfDay() : $now->copy()->addYears(5)
                );
            }

            return [
                'id' => $t->id,
                'image' => $t->banner ?? $t->logo,
                'title' => $t->title,
                'slug' => $t->slug,
                'format' => $t->format,
                'team_size' => $t->team_size,
                'location' => $t->location,
                'is_registration_open' => $isRegistrationOpen,
                'registration_start' => $t->registration_start,
                'registration_end' => $t->registration_end,
                'start_date' => $t->start_date,
                'end_date' => $t->end_date,
                 'prize_pool' => $t->prize_pool,
                'entry_fee' => $t->entry_fee,
                'start_time' => $t->start_time,
                'attendees' => $t->registered_participants,
                'max_participants' => $t->max_participants,
                'is_featured' => $t->is_featured,
                // 'status' => $t->status,
                'status_dynamic' => $status_dynamic,
                'game' => $t->game ? [
                    'id' => $t->game->id,
                    'name' => $t->game->name,
                    'slug' => $t->game->slug,
                    'logo' => $t->game->logo,
                ] : null,
            ];
        });

        return response()->json($response);
    }

    public function listByDate(Request $request)
    {
        $now = now();

        // Base query: include all tournaments
        $query = Tournament::with('game')
            ->where('visibility', 'published')
            ->orderByDesc('is_featured')
            ->orderBy('start_date', 'asc');

        // Date-based filter
        if ($request->filled('filter')) {
            $filter = strtolower($request->filter);

            if ($filter === 'live') {
                // Tournaments where start_date <= now <= end_date
                $query->where(function ($q) use ($now) {
                    $q->whereDate('start_date', '<=', $now)
                        ->whereDate('end_date', '>=', $now);
                });
            } elseif ($filter === 'upcoming') {
                // Tournaments starting in the future
                $query->whereDate('start_date', '>', $now);
            } elseif ($filter === 'completed') {
                // Tournaments already ended
                $query->whereDate('end_date', '<', $now);
            }
            // 'all' => no extra filter
        }

        $tournaments = $query->get();

        // Transform response safely
        $response = $tournaments->map(function ($t) use ($now) {

            // -----------------------------
            // Start datetime safe parsing
            // -----------------------------
            $start = null;
            if ($t->start_date) {
                $start = strlen($t->start_date) > 10
                    ? Carbon::parse($t->start_date)
                    : Carbon::parse($t->start_date . ' ' . ($t->start_time ?? '00:00:00'));
            }

            // -----------------------------
            // End datetime safe parsing
            // -----------------------------
            $end = null;
            if ($t->end_date) {
                $end = strlen($t->end_date) > 10
                    ? Carbon::parse($t->end_date)
                    : Carbon::parse($t->end_date . ' 23:59:59');
            }

            // -----------------------------
            // Dynamic status
            // -----------------------------
            if ($start && $end) {
                if ($now->lt($start)) {
                    $status_dynamic = 'upcoming';
                } elseif ($now->between($start, $end)) {
                    $status_dynamic = 'live';
                } else {
                    $status_dynamic = 'completed';
                }
            } else {
                $status_dynamic = $t->status;
            }

            return [
                'id' => $t->id,
                'image' => $t->banner ?? $t->logo,
                'title' => $t->title,
                'slug' => $t->slug,
                'format' => $t->format,
                'team_size' => $t->team_size,
                'location' => $t->location,
                'start_date' => $t->start_date,
                'end_date' => $t->end_date,
                'start_time' => $t->start_time,
                'prize_pool' => $t->prize_pool,
                'entry_fee' => $t->entry_fee,
                'attendees' => $t->registered_participants,
                'max_participants' => $t->max_participants,
                'is_featured' => $t->is_featured,
                'status_dynamic' => $status_dynamic,
                'game' => $t->game ? [
                    'id' => $t->game->id,
                    'name' => $t->game->name,
                    'slug' => $t->game->slug,
                    'logo' => $t->game->logo,
                ] : null,
            ];
        });

        return response()->json($response);
    }



    /**
     * Tournament search
     * GET /api/tournaments/search?query=valorant
     */
    public function search(Request $request)
    {
        $query = $request->query('query', '');

        $tournaments = Tournament::with('game')
            ->where('title', 'like', "%{$query}%")
            ->get();

        $response = $tournaments->map(function ($t) {
            return [
                'id' => $t->id,
                'image' => $t->banner ?? $t->logo,
                'title' => $t->title,
                'slug' => $t->slug,
                'location' => $t->location,
                'is_registration_open' => $t->is_registration_open,
                'registration_start' => $t->registration_start ? $t->registration_start->format('Y-m-d') : null,
                'registration_end' => $t->registration_end ? $t->registration_end->format('Y-m-d') : null,
                'start_date' => $t->start_date ? $t->start_date->format('Y-m-d') : null,
                'end_date' => $t->end_date ? $t->end_date->format('Y-m-d') : null,
                'start_time' => $t->start_time,
                'attendees' => $t->registered_participants,
                'max_participants' => $t->max_participants,
            ];
        });

        return response()->json($response);
    }

    /**
     * Tournament detail page
     * GET /api/tournaments/{id}
     */
  public function show($id)
    {
        $now  = now();
        $tournament = Tournament::with('game')->findOrFail($id);

        /**
         * --------------------------------------
         * Popular Matches
         * (Other tournaments of same game)
         * --------------------------------------
         */
        $popularMatches = Tournament::where('game_id', $tournament->game_id)
            ->where('id', '!=', $tournament->id)
            ->where('visibility', 'published')
            ->latest()
            ->limit(5)
            ->get([
                'id',
                'title',
                'slug',
                'prize_pool',
                'entry_fee',
                'start_date',
                'status',
                'logo'
            ]);

        /**
         * --------------------------------------
         * Popular Teams / Players
         * (Registered users / teams)
         * --------------------------------------
         */
        $popularTeams = TournamentRegistration::with('user:id,first_name,last_name,email')
            ->where('tournament_id', $tournament->id)
            ->get();

        $totalTeams = TournamentRegistration::with('user:id,first_name,last_name,email')
            ->where('tournament_id', $tournament->id)
            ->count();

        // Registration open logic
        $isRegistrationOpen = false;
            if ($tournament->registration_start || $tournament->registration_end) {
                $isRegistrationOpen = $now->between(
                    $tournament->registration_start ? Carbon::parse($tournament->registration_start)->startOfDay() : $now->copy()->subYears(5),
                    $tournament->registration_end ? Carbon::parse($tournament->registration_end)->endOfDay() : $now->copy()->addYears(5)
                );
        }

        return response()->json([
            'tournament' => [
                'id' => $tournament->id,
                'title' => $tournament->title,
                'slug' => $tournament->slug,
                'description' => $tournament->description,
                'rules' => $tournament->rules,
                'logo' => $tournament->logo,
                'banner' => $tournament->banner,
                'is_registration_open' =>$isRegistrationOpen,
                'registration_start' => $tournament->registration_start ?$tournament->registration_start->format('Y-m-d') : null,
                'registration_end' =>$tournament->registration_end ? $tournament->registration_end->format('Y-m-d') : null,
                'start_date' => $tournament->start_date ? $tournament->start_date->format('Y-m-d') : null,
                'end_date' => $tournament->end_date ? $tournament->end_date->format('Y-m-d') : null,
                'start_time' => $tournament->start_time,
                'entry_fees' => $tournament->entry_fee,
                'prize_pool' => $tournament->prize_pool,
                'format' => $tournament->format,
                'team_size' => $tournament->team_size,
                'status' => $tournament->status,
                'is_featured' => $tournament->is_featured,
                'game' => $tournament->game,
                'popular_matches' => $popularMatches,
                'popular_teams' => $popularTeams,
                'totalTeams' => $totalTeams
            ],


        ]);
    }

}