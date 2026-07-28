<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tournament;
use Illuminate\Support\Facades\DB;
use App\Models\TournamentRegistration;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserSocialLink;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;


class TournamentController extends Controller
{
    /**
     * List tournaments
     * GET /api/tournaments
     * Query params: search, game, filter (e.g., NearMe), status
     */


    // public function index(Request $request)
    // {
    //     $now  = now();
    //     $user = $request->user();
        
    //     $queryBuilder = Tournament::with('game')
    //         ->where('visibility', 'published')
    //         ->orderByDesc('id');   // default order (latest first)
        
    //     // Search by title or game name
    //     if ($request->filled('query')) {
    //         $searchTerm = '%' . $request->query('query') . '%';
    //         $queryBuilder->where(function ($query) use ($searchTerm) {
    //             $query->where('title', 'like', $searchTerm)
    //                   ->orWhereHas('game', function ($gameQuery) use ($searchTerm) {
    //                       $gameQuery->where('name', 'like', $searchTerm);
    //                   });
    //         });
    //     }
    
    //     // Filter logic (unchanged)
    //     if ($request->filled('filter')) {
    //         $filter = strtolower($request->filter);
    
    //         if ($filter === 'upcoming') {
    //             $queryBuilder->where('start_date', '>', $now);
    //         } elseif ($filter === 'live') {
    //             $queryBuilder->where('start_date', '<=', $now)
    //                 ->where('end_date', '>=', $now);
    //         } elseif ($filter === 'completed') {
    //             $queryBuilder->where('end_date', '<', $now);
    //         } elseif ($filter === 'nearby') {
    //             if ($user && $user->location) {
    //                 $queryBuilder->where('location', 'like', '%' . $user->location . '%');
    //             }
    //         } elseif ($filter !== 'all') {
    //             $queryBuilder->whereHas('game', function ($q) use ($filter) {
    //                 $q->where('name', 'like', '%' . $filter . '%');
    //             });
    //         }
    //     }
    
    //     $tournaments = $queryBuilder->get();
    
    //     // Map to response format (unchanged)
    //     $response = $tournaments->map(function ($t) use ($now) {
    //         $start = $t->start_date ? Carbon::parse($t->start_date) : null;
    //         $end   = $t->end_date ? Carbon::parse($t->end_date) : null;
    
    //         if ($start && $end) {
    //             if ($now->lt($start)) {
    //                 $status_dynamic = 'upcoming';
    //             } elseif ($now->between($start, $end)) {
    //                 $status_dynamic = 'live';
    //             } else {
    //                 $status_dynamic = 'completed';
    //             }
    //         } else {
    //             $status_dynamic = $t->status;
    //         }
    
    //         $isRegistrationOpen = false;
    //         if ($t->registration_start && $t->registration_end) {
    //             $regStart = Carbon::parse($t->registration_start);
    //             $regEnd   = Carbon::parse($t->registration_end);
    //             if ($now->between($regStart, $regEnd)) {
    //                 $isRegistrationOpen = true;
    //             }
    //         }
    
    //         return [
    //             'id' => $t->id,
    //             'image' => $t->banner ?? $t->logo,
    //             'title' => $t->title,
    //             'slug' => $t->slug,
    //             'format' => $t->format,
    //             'team_size' => $t->team_size,
    //             'location' => $t->location,
    //             'is_registration_open' => $isRegistrationOpen,
    //             'registration_start' => $t->registration_start,
    //             'registration_end' => $t->registration_end,
    //             'start_date' => $t->start_date,
    //             'end_date' => $t->end_date,
    //             'prize_pool' => $t->prize_pool,
    //             'entry_fee' => $t->entry_fee,
    //             'attendees' => $t->registered_participants,
    //             'max_participants' => $t->max_participants,
    //             'social_links' => $t->social_links,
    //             'stream_url' => $t->stream_url,
    //             'is_featured' => $t->is_featured,
    //             'status_dynamic' => $status_dynamic,
    //             'game' => $t->game ? [
    //                 'id' => $t->game->id,
    //                 'name' => $t->game->name,
    //                 'slug' => $t->game->slug,
    //                 'logo' => $t->game->logo,
    //             ] : null,
    //         ];
    //     });
    
    //     // ----- NEW SORTING WITH URGENCY -----
    //     // 1. Open registration first (is_registration_open = true)
    //     // 2. Among open, sort by registration_end ascending (soonest closing first)
    //     // 3. For ties, sort by id descending (latest first)
    //     $sorted = $response->sortBy(function ($item) {
    //         $openScore = $item['is_registration_open'] ? 0 : 1;   // 0 first
    //         $endDate   = $item['registration_end'] ?? '9999-12-31 23:59:59'; // nulls last
    //         $idScore   = -$item['id'];                            // descending id
    //         return [$openScore, $endDate, $idScore];
    //     })->values(); // reset numeric keys
    
    //     return response()->json($sorted);
    // }
    
    public function index(Request $request)
    {
        $now  = now();
        $user = $request->user();
        
        $queryBuilder = Tournament::with('game')
            ->where('visibility', 'published');
        
        // Search by title or game name
        if ($request->filled('query')) {
            $searchTerm = '%' . $request->query('query') . '%';
            $queryBuilder->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', $searchTerm)
                      ->orWhereHas('game', function ($gameQuery) use ($searchTerm) {
                          $gameQuery->where('name', 'like', $searchTerm);
                      });
            });
        }
    
        // Filter logic (unchanged)
        if ($request->filled('filter')) {
            $filter = strtolower($request->filter);
    
            if ($filter === 'upcoming') {
                $queryBuilder->where('start_date', '>', $now);
            } elseif ($filter === 'live') {
                $queryBuilder->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
            } elseif ($filter === 'completed') {
                $queryBuilder->where('end_date', '<', $now);
            } elseif ($filter === 'nearby') {
                if ($user && $user->location) {
                    $queryBuilder->where('location', 'like', '%' . $user->location . '%');
                }
            } elseif ($filter !== 'all') {
                $queryBuilder->whereHas('game', function ($q) use ($filter) {
                    $q->where('name', 'like', '%' . $filter . '%');
                });
            }
        }
    
        $tournaments = $queryBuilder->get();
    
        // Map to response format (unchanged)
        $response = $tournaments->map(function ($t) use ($now) {
            $start = $t->start_date ? Carbon::parse($t->start_date) : null;
            $end   = $t->end_date ? Carbon::parse($t->end_date) : null;
    
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
    
            $isRegistrationOpen = false;
            if ($t->registration_start && $t->registration_end) {
                $regStart = Carbon::parse($t->registration_start);
                $regEnd   = Carbon::parse($t->registration_end);
                if ($now->between($regStart, $regEnd)) {
                    $isRegistrationOpen = true;
                }
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
                'attendees' => $t->registered_participants,
                'max_participants' => $t->max_participants,
                'social_links' => $t->social_links,
                'stream_url' => $t->stream_url,
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
    
        // ----- UPDATED SORTING LOGIC -----
        // 1. Open registration first (is_registration_open = true)
        // 2. Among open registrations: sort by registration_end DESC (latest end date first)
        // 3. Among closed registrations: sort by registration_end DESC (latest end date first)
        // 4. For ties, sort by id descending (latest first)
        $sorted = $response->sort(function ($a, $b) {
            // First: Open registration comes first
            if ($a['is_registration_open'] && !$b['is_registration_open']) {
                return -1;
            }
            if (!$a['is_registration_open'] && $b['is_registration_open']) {
                return 1;
            }
    
            // Both have same registration status (both open or both closed)
            // Sort by registration_end DESC (latest end date first)
            $aEnd = $a['registration_end'] ? Carbon::parse($a['registration_end'])->timestamp : 0;
            $bEnd = $b['registration_end'] ? Carbon::parse($b['registration_end'])->timestamp : 0;
    
            if ($aEnd !== $bEnd) {
                return $bEnd - $aEnd; // Descending order
            }
    
            // If registration_end is same, sort by id DESC (latest first)
            return $b['id'] - $a['id'];
        })->values(); // reset numeric keys
    
        return response()->json($sorted);
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
            
            $end = $t->end_date ? Carbon::parse($t->end_date) : null;

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
    
    public function search(Request $request)
    {
        $query = $request->query('query', '');
        $now = now();  // current time for registration logic
    
        $tournaments = Tournament::with('game')
            ->where('title', 'like', "%{$query}%")
            ->get();
    
        $response = $tournaments->map(function ($t) use ($now) {
            
            // Rgistration open logic (same as index method)
            $isRegistrationOpen = false;
            if ($t->registration_start && $t->registration_end) {
                $regStart = Carbon::parse($t->registration_start);
                $regEnd   = Carbon::parse($t->registration_end);
                if ($now->between($regStart, $regEnd)) {
                    $isRegistrationOpen = true;
                }
            }
    
            return [
                'id' => $t->id,
                'image' => $t->banner ?? $t->logo,
                'title' => $t->title,
                'slug' => $t->slug,
                'location' => $t->location,
                'is_registration_open' => $isRegistrationOpen,
                // Full datetime (UTC ISO) â€“ same as listing
                'registration_start' => $t->registration_start,
                'registration_end'   => $t->registration_end,
                'start_date'         => $t->start_date,
                'end_date'           => $t->end_date,
                
                // 'start_time' removed â€“ not needed
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
     
     private function getPopularTeams($tournamentId)
    {
        // Fetch ONLY status 1 registrations (team and solo) with user data
        $registrations = TournamentRegistration::with(['user' => function ($q) {
            $q->select('id', 'first_name', 'last_name', 'email', 'username', 'mobile');
        }])
        ->where('tournament_id', $tournamentId)
        ->where('status', 1) 
        ->get();
        
        $teams = [];
        
        foreach ($registrations as $reg) {
            // Determine grouping key based on type
            if ($reg->type === 'solo') {
                // For solo, group by user_id (each user is a separate "team")
                $user = $reg->user;
                $teamKey = 'solo_' . ($user ? $user->id : $reg->id);
                $teamName = $user ? $user->first_name . ' ' . $user->last_name : 'Solo Player';
                $teamTag = 'Solo';
                $teamLogo = null; // solo players don't have team logo
                $isCaptain = 0;
            } else {
                // For team, group by team_name and tag
                $teamKey = $reg->team_name . '|' . ($reg->team_tag ?? '');
                $teamName = $reg->team_name;
                $teamTag = $reg->team_tag;
                $teamLogo = $reg->team_logo;
                $isCaptain = $reg->is_captain;
            }
            
            // Initialize if not exists
            if (!isset($teams[$teamKey])) {
                $teams[$teamKey] = [
                    'team_name' => $teamName,
                    'team_tag' => $teamTag ?? null,
                    'team_logo' => $teamLogo,
                    'type' => $reg->type, // 'solo' or 'team'
                    'status' => $reg->status,
                    'members' => []
                ];
            }
            
            // Add member details if user exists
            if ($reg->user) {
                $user = $reg->user;
                $member = [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'mobile' => $user->mobile,
                    'profile_image' => $this->getUserProfileImage($user->id),
                    'social_links' => $this->getUserSocialLinks($user->id),
                    'is_captain' => $reg->is_captain ?? 0, // ✅ Added is_captain for each member
                ];
                // Optionally include registration-specific fields (phone, email)
                if ($reg->phone) {
                    $member['registration_phone'] = $reg->phone;
                }
                if ($reg->email) {
                    $member['registration_email'] = $reg->email;
                }
                $teams[$teamKey]['members'][] = $member;
            }
        }
        
        // Convert to indexed array and limit if needed (e.g., top 10)
        $result = array_values($teams);
        // Optionally sort by something, but keep as is
        return $result;
    }
    
    public function show($id)
    {
        $now = now();
        $tournament = Tournament::with('game')->findOrFail($id);
    
        // Popular Matches (other tournaments of same game)
        $popularMatches = Tournament::where('game_id', $tournament->game_id)
            ->where('id', '!=', $tournament->id)
            ->where('visibility', 'published')
            ->latest()
            ->limit(5)
            ->get(['id', 'title', 'slug', 'prize_pool', 'entry_fee', 'start_date', 'status', 'logo']);
    
        // Registration Open Check
        $isRegistrationOpen = false;
        if ($tournament->registration_start && $tournament->registration_end) {
            $regStart = Carbon::parse($tournament->registration_start);
            $regEnd   = Carbon::parse($tournament->registration_end);
            if ($now->between($regStart, $regEnd)) {
                $isRegistrationOpen = true;
            }
        }
    
        // Popular Teams – Fetch team registrations with participants (only status 1)
        $popularTeams = $this->getPopularTeams($tournament->id);
    
        // Total Teams (unique team names) - only status 1
        $totalTeams = TournamentRegistration::where('tournament_id', $tournament->id)
            ->where('type', 'team')
            ->where('status', 1) 
            ->distinct('team_name')
            ->count('team_name');
            
        // Total Solo registrations - only status 1
        $totalSolos = TournamentRegistration::where('tournament_id', $tournament->id)
            ->where('type', 'solo')
            ->where('status', 1) 
            ->count();
    
        $registeredUsers = User::whereHas('tournamentRegistrations', function ($query) use ($tournament) {
            $query->where('tournament_id', $tournament->id)
                  ->where('status', 1);
        })
        ->with(['tournamentRegistrations' => function ($query) use ($tournament) {
            $query->where('tournament_id', $tournament->id)
                  ->where('status', 1)
                  ->select('id', 'user_id', 'tournament_id', 'type', 'team_name', 'is_captain', 'status');
        }])
        ->get([
            'id',
            'first_name',
            'last_name',
            'username',
            'email',
            'mobile',
            'status',
            'created_at',
            'updated_at'
        ]);
    
        // ✅ Format registered users to include is_captain and registration details
        $formattedRegisteredUsers = $registeredUsers->map(function ($user) {
            $registration = $user->tournamentRegistrations->first();
            return [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'username' => $user->username,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'status' => $user->status,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'is_captain' => $registration->is_captain ?? 0, // ✅ Added is_captain
                'registration_type' => $registration->type ?? null,
                'team_name' => $registration->team_name ?? null,
            ];
        });
    
        return response()->json([
            'tournament' => [
                'id' => $tournament->id,
                'title' => $tournament->title,
                'slug' => $tournament->slug,
                'description' => $tournament->description,
                'rules' => $tournament->rules,
                'logo' => $tournament->logo,
                'banner' => $tournament->banner,
                'is_registration_open' => $isRegistrationOpen,
                'registration_start' => $tournament->registration_start,
                'registration_end'   => $tournament->registration_end,
                'start_date'         => $tournament->start_date,
                'end_date'           => $tournament->end_date,
                'entry_fees'   => $tournament->entry_fee,
                'prize_pool'   => $tournament->prize_pool,
                'format'       => $tournament->format,
                'team_size'    => $tournament->team_size,
                'max_participants' => $tournament->max_participants,
                'status'       => $tournament->status,
                'is_featured'  => $tournament->is_featured,
                'allow_pdf_download' => $tournament->allow_pdf_download,
    
                'game'           => $tournament->game,
                'popular_matches'=> $popularMatches,
                'popular_teams'  => $popularTeams,
                'totalTeams' => $totalTeams,
                'totalSolos' => $totalSolos,
                'social_links' => $tournament->social_links,
                'stream_url' => $tournament->stream_url,
    
                'registered_users' => $formattedRegisteredUsers,
            ],
        ]);
    }
     
    //  private function getPopularTeams($tournamentId)
    // {
    //     // Fetch ONLY status 1 registrations (team and solo) with user data
    //     $registrations = TournamentRegistration::with(['user' => function ($q) {
    //         $q->select('id', 'first_name', 'last_name', 'email', 'username', 'mobile');
    //     }])
    //     ->where('tournament_id', $tournamentId)
    //     ->where('status', 1) 
    //     ->get();
    //     $teams = [];
        
    //     foreach ($registrations as $reg) {
    //         // Determine grouping key based on type
    //         if ($reg->type === 'solo') {
    //             // For solo, group by user_id (each user is a separate "team")
    //             $user = $reg->user;
    //             $teamKey = 'solo_' . ($user ? $user->id : $reg->id);
    //             $teamName = $user ? $user->first_name . ' ' . $user->last_name : 'Solo Player';
    //             $teamTag = 'Solo';
    //             $teamLogo = null; // solo players don't have team logo
    //             $isCaptain = 0;
    //         } else {
    //             // For team, group by team_name and tag
    //             $teamKey = $reg->team_name . '|' . ($reg->team_tag ?? '');
    //             $teamName = $reg->team_name;
    //             $teamTag = $reg->team_tag;
    //             $teamLogo = $reg->team_logo;
    //             $isCaptain = $reg->is_captain;
    //         }
            
    //         // Initialize if not exists
    //         if (!isset($teams[$teamKey])) {
    //             $teams[$teamKey] = [
    //                 'team_name' => $teamName,
    //                 'team_tag' => $teamTag ?? null,
    //                 'team_logo' => $teamLogo,
    //                 'type' => $reg->type, // 'solo' or 'team'
    //                 'is_captain' => $isCaptain,
    //                 'status' => $reg->status,
    //                 'members' => []
    //             ];
    //         }
            
    //         // Add member details if user exists
    //         if ($reg->user) {
    //             $user = $reg->user;
    //             $member = [
    //                 'id' => $user->id,
    //                 'first_name' => $user->first_name,
    //                 'last_name' => $user->last_name,
    //                 'email' => $user->email,
    //                 'username' => $user->username,
    //                 'mobile' => $user->mobile,
    //                 'profile_image' => $this->getUserProfileImage($user->id),
    //                 'social_links' => $this->getUserSocialLinks($user->id),
    //             ];
    //             // Optionally include registration-specific fields (phone, email)
    //             if ($reg->phone) {
    //                 $member['registration_phone'] = $reg->phone;
    //             }
    //             if ($reg->email) {
    //                 $member['registration_email'] = $reg->email;
    //             }
    //             $teams[$teamKey]['members'][] = $member;
    //         }
    //     }
        
    //     // Convert to indexed array and limit if needed (e.g., top 10)
    //     $result = array_values($teams);
    //     // Optionally sort by something, but keep as is
    //     return $result;
    // }

    // public function show($id)
    // {
    //     $now = now();
    //     $tournament = Tournament::with('game')->findOrFail($id);
    
    //     // Popular Matches (other tournaments of same game)
    //     $popularMatches = Tournament::where('game_id', $tournament->game_id)
    //         ->where('id', '!=', $tournament->id)
    //         ->where('visibility', 'published')
    //         ->latest()
    //         ->limit(5)
    //         ->get(['id', 'title', 'slug', 'prize_pool', 'entry_fee', 'start_date', 'status', 'logo']);
    
    //     // Registration Open Check
    //     $isRegistrationOpen = false;
    //     if ($tournament->registration_start && $tournament->registration_end) {
    //         $regStart = Carbon::parse($tournament->registration_start);
    //         $regEnd   = Carbon::parse($tournament->registration_end);
    //         if ($now->between($regStart, $regEnd)) {
    //             $isRegistrationOpen = true;
    //         }
    //     }
    
    //     // Popular Teams – Fetch team registrations with participants (only status 1)
    //     $popularTeams = $this->getPopularTeams($tournament->id);
    
    //     // Total Teams (unique team names) - only status 1
    //     $totalTeams = TournamentRegistration::where('tournament_id', $tournament->id)
    //         ->where('type', 'team')
    //         ->where('status', 1) 
    //         ->distinct('team_name')
    //         ->count('team_name');
            
    //     // Total Solo registrations - only status 1
    //     $totalSolos = TournamentRegistration::where('tournament_id', $tournament->id)
    //         ->where('type', 'solo')
    //         ->where('status', 1) 
    //         ->count();
    
    //     // Fetch registered users with status 1 registrations only
    //     $registeredUsers = User::whereHas('tournamentRegistrations', function ($query) use ($tournament) {
    //         $query->where('tournament_id', $tournament->id)
    //               ->where('status', 1); 
    //     })->get([
    //         'id',
    //         'first_name',
    //         'last_name',
    //         'username',
    //         'email',
    //         'mobile',
    //         'status',
    //         'created_at',
    //         'updated_at'
    //     ]);
    
    //     return response()->json([
    //         'tournament' => [
    //             'id' => $tournament->id,
    //             'title' => $tournament->title,
    //             'slug' => $tournament->slug,
    //             'description' => $tournament->description,
    //             'rules' => $tournament->rules,
    //             'logo' => $tournament->logo,
    //             'banner' => $tournament->banner,
    //             'is_registration_open' => $isRegistrationOpen,
    //             'registration_start' => $tournament->registration_start,
    //             'registration_end'   => $tournament->registration_end,
    //             'start_date'         => $tournament->start_date,
    //             'end_date'           => $tournament->end_date,
    //             'entry_fees'   => $tournament->entry_fee,
    //             'prize_pool'   => $tournament->prize_pool,
    //             'format'       => $tournament->format,
    //             'team_size'    => $tournament->team_size,
    //             'max_participants' => $tournament->max_participants,
    //             'status'       => $tournament->status,
    //             'is_featured'  => $tournament->is_featured,
    //             'allow_pdf_download' => $tournament->allow_pdf_download,
    
    //             'game'           => $tournament->game,
    //             'popular_matches'=> $popularMatches,
    //             'popular_teams'  => $popularTeams,
    //             'totalTeams' => $totalTeams,
    //             'totalSolos' => $totalSolos,
    //             'social_links' => $tournament->social_links,
    //             'stream_url' => $tournament->stream_url,
    
    //             // registered users with active registrations only
    //             'registered_users' => $registeredUsers,
    //         ],
    //     ]);
    // }
   
//     public function show($id)
    // {
    //     $now = now();
    //     $tournament = Tournament::with('game')->findOrFail($id);
    
    //     //  Popular Matches (other tournaments of same game)
    //     $popularMatches = Tournament::where('game_id', $tournament->game_id)
    //         ->where('id', '!=', $tournament->id)
    //         ->where('visibility', 'published')
    //         ->latest()
    //         ->limit(5)
    //         ->get(['id', 'title', 'slug', 'prize_pool', 'entry_fee', 'start_date', 'status', 'logo']);
    
    //     //  Registration Open Check
    //     $isRegistrationOpen = false;
    //     if ($tournament->registration_start && $tournament->registration_end) {
    //         $regStart = Carbon::parse($tournament->registration_start);
    //         $regEnd   = Carbon::parse($tournament->registration_end);
    //         if ($now->between($regStart, $regEnd)) {
    //             $isRegistrationOpen = true;
    //         }
    //     }
    
    //     //  Popular Teams â€“ Fetch team registrations with participants
    //     $popularTeams = $this->getPopularTeams($tournament->id);
    
    //     //  Total Teams (unique team names)
    //     $totalTeams = TournamentRegistration::where('tournament_id', $tournament->id)
    //         ->where('type', 'team')
    //         ->distinct('team_name')
    //         ->count('team_name');
            
    //     // Total Solo registrations
    //     $totalSolos = TournamentRegistration::where('tournament_id', $tournament->id)
    //         ->where('type', 'solo')
    //         ->count();
    
    //     // ðŸ‘‡ NEW: Fetch registered users (users table details)
    //     $registeredUsers = User::whereHas('tournamentRegistrations', function ($query) use ($tournament) {
    //         $query->where('tournament_id', $tournament->id);
    //     })->get([
    //         'id',
    //         'first_name',
    //         'last_name',
    //         'username',
    //         'email',
    //         'mobile',
    //         'status',
    //         'created_at',
    //         'updated_at'
    //     ]);
    
    //     return response()->json([
    //         'tournament' => [
    //             'id' => $tournament->id,
    //             'title' => $tournament->title,
    //             'slug' => $tournament->slug,
    //             'description' => $tournament->description,
    //             'rules' => $tournament->rules,
    //             'logo' => $tournament->logo,
    //             'banner' => $tournament->banner,
    //             'is_registration_open' => $isRegistrationOpen,
    //             'registration_start' => $tournament->registration_start,
    //             'registration_end'   => $tournament->registration_end,
    //             'start_date'         => $tournament->start_date,
    //             'end_date'           => $tournament->end_date,
    //             'entry_fees'   => $tournament->entry_fee,
    //             'prize_pool'   => $tournament->prize_pool,
    //             'format'       => $tournament->format,
    //             'team_size'    => $tournament->team_size,
    //             'max_participants' => $tournament->max_participants,
    //             'status'       => $tournament->status,
    //             'is_featured'  => $tournament->is_featured,
    //             'allow_pdf_download' => $tournament->allow_pdf_download,
    
    //             'game'           => $tournament->game,
    //             'popular_matches'=> $popularMatches,
    //             'popular_teams'  => $popularTeams,
    //             'totalTeams' => $totalTeams,
    //             'totalSolos' => $totalSolos,
    //             'social_links' => $tournament->social_links,
    //             'stream_url' => $tournament->stream_url,
    
    //             // ðŸ‘‡ NEW: registered users array
    //             'registered_users' => $registeredUsers,
    //         ],
    //     ]);
    // }

    /**
     * Helper: Get all registrations (both team and solo) with participant details
     * For teams: grouped by team name; for solo: each as a separate "team"
     */
    // private function getPopularTeams($tournamentId)
    // {
    //     // Fetch ALL registrations (team and solo) with user data
    //     $registrations = TournamentRegistration::with(['user' => function ($q) {
    //         $q->select('id', 'first_name', 'last_name', 'email', 'username', 'mobile');
    //     }])
    //     ->where('tournament_id', $tournamentId)
    //     ->get(); // No type filter
    
    //     $teams = [];
    
    //     foreach ($registrations as $reg) {
    //         // Determine grouping key based on type
    //         if ($reg->type === 'solo') {
    //             // For solo, group by user_id (each user is a separate "team")
    //             $user = $reg->user;
    //             $teamKey = 'solo_' . ($user ? $user->id : $reg->id);
    //             $teamName = $user ? $user->first_name . ' ' . $user->last_name : 'Solo Player';
    //             $teamTag = 'Solo';
    //             $teamLogo = null; // solo players don't have team logo
    //             $isCaptain = 0;
    //         } else {
    //             // For team, group by team_name and tag
    //             $teamKey = $reg->team_name . '|' . ($reg->team_tag ?? '');
    //             $teamName = $reg->team_name;
    //             $teamTag = $reg->team_tag;
    //             $teamLogo = $reg->team_logo;
    //             $isCaptain = $reg->is_captain;
    //         }
    
    //         // Initialize if not exists
    //         if (!isset($teams[$teamKey])) {
    //             $teams[$teamKey] = [
    //                 'team_name' => $teamName,
    //                 'team_tag' => $teamTag ?? null,
    //                 'team_logo' => $teamLogo,
    //                 'type' => $reg->type, // 'solo' or 'team'
    //                 'is_captain' => $isCaptain,
    //                 'status' => $reg->status,
    //                 'members' => []
    //             ];
    //         }
    
    //         // Add member details if user exists
    //         if ($reg->user) {
    //             $user = $reg->user;
    //             $member = [
    //                 'id' => $user->id,
    //                 'first_name' => $user->first_name,
    //                 'last_name' => $user->last_name,
    //                 'email' => $user->email,
    //                 'username' => $user->username,
    //                 'mobile' => $user->mobile,
    //                 'profile_image' => $this->getUserProfileImage($user->id),
    //                 'social_links' => $this->getUserSocialLinks($user->id),
    //             ];
    //             // Optionally include registration-specific fields (phone, email)
    //             if ($reg->phone) {
    //                 $member['registration_phone'] = $reg->phone;
    //             }
    //             if ($reg->email) {
    //                 $member['registration_email'] = $reg->email;
    //             }
    //             $teams[$teamKey]['members'][] = $member;
    //         }
    //     }
    
    //     // Convert to indexed array and limit if needed (e.g., top 10)
    //     $result = array_values($teams);
    //     // Optionally sort by something, but keep as is
    //     return $result;
    // }

    /**
     * Get user profile image from user_profiles table
     */
    private function getUserProfileImage($userId)
    {
        $profile = UserProfile::where('user_id', $userId)->first();
        return $profile ? $profile->profile_image : null;
    }

    /**
     * Get user social links from user_social_links table
     */
    private function getUserSocialLinks($userId)
    {
        $links = UserSocialLink::where('user_id', $userId)->first();
        if (!$links) {
            return null;
        }
        // Return only non-null fields
        return array_filter([
            'facebook' => $links->facebook,
            'instagram' => $links->instagram,
            'twitter' => $links->twitter,
            'youtube' => $links->youtube,
            'discord' => $links->discord,
            'twitch' => $links->twitch,
        ]);
    }

    // public function winners(Request $request)
    // {
    //     $now = now();
    
    //     // Base query
    //     $query = Tournament::with(['game', 'registrations.user'])
    //         ->where('visibility', 'published')
    //         ->whereNotNull('winner_team_name')
    //         ->withCount(['registrations' => function ($q) {
    //             $q->where('status', 1);
    //         }]);
    
    //     // ---------- Filters & Search (customize as needed) ----------
    //     if ($request->filled('search')) {
    //         $search = $request->search;
    //         $query->where(function ($q) use ($search) {
    //             $q->where('title', 'LIKE', "%{$search}%")
    //               ->orWhere('winner_team_name', 'LIKE', "%{$search}%")
    //               ->orWhere('prize_pool', 'LIKE', "%{$search}%");
    //         });
    //     }
    
    //     if ($request->filled('game_id')) {
    //         $query->where('game_id', $request->game_id);
    //     }
    
    //     if ($request->filled('date_from')) {
    //         $query->whereDate('start_date', '>=', $request->date_from);
    //     }
    
    //     if ($request->filled('date_to')) {
    //         $query->whereDate('end_date', '<=', $request->date_to);
    //     }
    
    //     // Sorting (default: newest first)
    //     $sortBy = $request->get('sort_by', 'created_at');
    //     $sortOrder = $request->get('sort_order', 'desc');
    //     $query->orderBy($sortBy, $sortOrder);
    
    //     // Paginate
    //     $tournaments = $query->paginate(10);
    
    //     // Map results
    //     $response = $tournaments->map(function ($t) {
    //         // Get all registrations for the winning team (status=1)
    //         $winnerRegistrations = $t->registrations
    //             ->where('team_name', $t->winner_team_name)
    //             ->where('status', 1);
    
    //         // ----- Members list (without profile_image & social_links) -----
    //         $members = $winnerRegistrations->map(function ($reg) {
    //             $user = $reg->user;
    //             if (!$user) return null;
    
    //             return [
    //                 'id'         => $user->id,
    //                 'first_name' => $user->first_name,
    //                 'last_name'  => $user->last_name,
    //                 'username'   => $user->username,
    //                 'is_captain' => (bool) $reg->is_captain,
    //             ];
    //         })->filter()->values();
    
    //         // ----- Find the captain (is_captain = 1) -----
    //         $captainReg = $winnerRegistrations->firstWhere('is_captain', 1);
    //         $captainUser = $captainReg ? $captainReg->user : null;
    
    //         $captainData = $captainUser ? [
    //             'id'            => $captainUser->id,
    //             'first_name'    => $captainUser->first_name,
    //             'last_name'     => $captainUser->last_name,
    //             'username'      => $captainUser->username,
    //             'profile_image' => $this->getUserProfileImage($captainUser->id), // should return full URL
    //             'social_links'  => $this->getUserSocialLinks($captainUser->id),  // should return full URLs
    //         ] : null;
    
    //         // ----- Tournament logo (absolute URL) -----
    //         $tournamentLogo = $t->logo ? Storage::disk('public')->url($t->logo) : null;
    //         // Alternative: asset('storage/' . $t->logo)
    
    //         // ----- Team type and team logo (absolute URL) -----
    //         $firstWinnerReg = $winnerRegistrations->first();
    //         $teamType = $firstWinnerReg ? $firstWinnerReg->type : null;
    //         $teamLogo = $firstWinnerReg && $firstWinnerReg->team_logo
    //             ? Storage::disk('public')->url($firstWinnerReg->team_logo)
    //             : null;
    
    //         return [
    //             'id'    => $t->id,
    //             'title' => $t->title,
    //             'game'  => $t->game ? $t->game->name : null,
    //             'date'  => $t->end_date ?? $t->start_date,
    //             'total_participants' => $t->registrations_count,
    //             'logo'   => $tournamentLogo,
    //             'winner' => [
    //                 'team_name' => $t->winner_team_name,
    //                 'prize'     => $t->prize_pool ?? 0,
    //                 'team_type' => $teamType,
    //                 'team_logo' => $teamLogo,
    //                 'captain'   => $captainData,
    //                 'members'   => $members,
    //             ],
    //         ];
    //     });
    
    //     return response()->json([
    //         'data' => $response,
    //         'meta' => [
    //             'current_page' => $tournaments->currentPage(),
    //             'last_page'    => $tournaments->lastPage(),
    //             'total'        => $tournaments->total(),
    //         ],
    //     ]);
    // }
//     public function winners(Request $request)
//     {
//         $now = now();
    
//         // Base query
//         $query = Tournament::with(['game', 'registrations.user'])
//             ->where('visibility', 'published')
//             ->whereNotNull('winner_team_name')
//             ->withCount(['registrations' => function ($q) {
//                 $q->where('status', 1);
//             }]);
    
//         // ---------- Filters & Search ----------
//         if ($request->filled('search')) {
//             $search = $request->search;
//             $query->where(function ($q) use ($search) {
//                 $q->where('title', 'LIKE', "%{$search}%")
//                   ->orWhere('winner_team_name', 'LIKE', "%{$search}%")
//                   ->orWhere('prize_pool', 'LIKE', "%{$search}%");
//             });
//         }
    
//         if ($request->filled('game_id')) {
//             $query->where('game_id', $request->game_id);
//         }
    
//         if ($request->filled('date_from')) {
//             $query->whereDate('start_date', '>=', $request->date_from);
//         }
    
//         if ($request->filled('date_to')) {
//             $query->whereDate('end_date', '<=', $request->date_to);
//         }
    
//         // Sorting (default: newest first)
//         $sortBy = $request->get('sort_by', 'created_at');
//         $sortOrder = $request->get('sort_order', 'desc');
//         $query->orderBy($sortBy, $sortOrder);
    
//         // Paginate
//         $tournaments = $query->paginate(10);
    
//         // Map results
//         $response = $tournaments->map(function ($t) {
//             // Get all registrations for the winning team (status=1)
//             $winnerRegistrations = $t->registrations
//                 ->where('team_name', $t->winner_team_name)
//                 ->where('status', 1);
    
//             // ----- Members list with prize details -----
//             $members = $winnerRegistrations->map(function ($reg) {
//                 $user = $reg->user;
//                 if (!$user) return null;
    
//                 // Handle prize_distributed_at properly
//                 $prizeDistributedAt = null;
//                 if ($reg->prize_distributed_at) {
//                     if ($reg->prize_distributed_at instanceof \Carbon\Carbon) {
//                         $prizeDistributedAt = $reg->prize_distributed_at->toDateTimeString();
//                     } else {
//                         // If it's a string, keep it as is or parse it
//                         $prizeDistributedAt = (string) $reg->prize_distributed_at;
//                     }
//                 }
    
//                 return [
//                     'id'                    => $user->id,
//                     'first_name'            => $user->first_name,
//                     'last_name'             => $user->last_name,
//                     'username'              => $user->username,
//                     'is_captain'            => (bool) $reg->is_captain,
//                     'prize_amount'          => $reg->prize_amount ? (float) $reg->prize_amount : 0,
//                     'prize_rank'            => $reg->prize_rank,
//                     'prize_distributed_at'  => $prizeDistributedAt,
//                 ];
//             })->filter()->values();
    
//             // ----- Find the captain (is_captain = 1) -----
//             $captainReg = $winnerRegistrations->firstWhere('is_captain', 1);
//             $captainUser = $captainReg ? $captainReg->user : null;
// //             $captainData = $captainUser ? [
//                 'id'            => $captainUser->id,
//                 'first_name'    => $captainUser->first_name,
//                 'last_name'     => $captainUser->last_name,
//                 'username'      => $captainUser->username,
//                 'profile_image' => $this->getUserProfileImage($captainUser->id),
//                 'social_links'  => $this->getUserSocialLinks($captainUser->id),
//                 'prize_amount'  => $captainReg->prize_amount ? (float) $captainReg->prize_amount : 0,
//                 'prize_rank'    => $captainReg->prize_rank,
//             ] : null;
    
//             // ----- Tournament logo (absolute URL) -----
//             $tournamentLogo = $t->logo ? Storage::disk('public')->url($t->logo) : null;
    
//             // ----- Team type and team logo (absolute URL) -----
//             $firstWinnerReg = $winnerRegistrations->first();
//             $teamType = $firstWinnerReg ? $firstWinnerReg->type : null;
//             $teamLogo = $firstWinnerReg && $firstWinnerReg->team_logo
//                 ? Storage::disk('public')->url($firstWinnerReg->team_logo)
//                 : null;
    
//             // ----- Calculate total distributed and remaining -----
//             $totalDistributed = $winnerRegistrations->sum('prize_amount') ?? 0;
//             $totalPrizePool = $t->prize_pool ?? 0;
//             $remainingPrize = $totalPrizePool - $totalDistributed;
    
//             // ----- Prize distribution summary -----
//             $prizeDistribution = [
//                 'total_prize_pool' => (float) $totalPrizePool,
//                 'total_distributed' => (float) $totalDistributed,
//                 'remaining' => (float) $remainingPrize,
//                 'is_fully_distributed' => $remainingPrize <= 0,
//                 'distributed_to' => $winnerRegistrations->where('prize_amount', '>', 0)->count(),
//                 'total_members' => $winnerRegistrations->count(),
//             ];
    
//             // ----- Rank wise distribution (grouped by rank) -----
//             $rankWiseDistribution = $winnerRegistrations
//                 ->whereNotNull('prize_rank')
//                 ->where('prize_amount', '>', 0)
//                 ->groupBy('prize_rank')
//                 ->map(function ($group) {
//                     return [
//                         'count' => $group->count(),
//                         'total_amount' => (float) $group->sum('prize_amount'),
//                         'members' => $group->map(function ($reg) {
//                             return [
//                                 'name' => $reg->user ? trim(($reg->user->first_name ?? '') . ' ' . ($reg->user->last_name ?? '')) : ($reg->name ?? 'Unknown'),
//                                 'amount' => (float) $reg->prize_amount,
//                             ];
//                         })->values(),
//                     ];
//                 });
    
//             return [
//                 'id'    => $t->id,
//                 'title' => $t->title,
//                 'game'  => $t->game ? $t->game->name : null,
//                 'date'  => $t->end_date ?? $t->start_date,
//                 'total_participants' => $t->registrations_count,
//                 'logo'   => $tournamentLogo,
//                 'winner' => [
//                     'team_name' => $t->winner_team_name,
//                     'prize_pool' => (float) $t->prize_pool ?? 0,
//                     'team_type' => $teamType,
//                     'team_logo' => $teamLogo,
//                     'captain'   => $captainData,
//                     'members'   => $members,
//                     'prize_distribution' => $prizeDistribution,
//                     // 'rank_wise_distribution' => $rankWiseDistribution,
//                 ],
//             ];
//         });
    
//         return response()->json([
//             'data' => $response,
//             'meta' => [
//                 'current_page' => $tournaments->currentPage(),
//                 'last_page'    => $tournaments->lastPage(),
//                 'total'        => $tournaments->total(),
//             ],
//         ]);
//     }

    public function winners(Request $request)
    {
        $now = now();
    
        // Base query with proper eager loading
        $query = Tournament::with([
            'game', 
            'registrations' => function ($q) {
                $q->where('status', 1);
            },
            'registrations.user'
        ])
        ->where('visibility', 'published')
        ->whereNotNull('winner_team_name')
        ->withCount(['registrations' => function ($q) {
            $q->where('status', 1);
        }]);
    
        // ---------- Filters & Search ----------
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('winner_team_name', 'LIKE', "%{$search}%")
                  ->orWhere('prize_pool', 'LIKE', "%{$search}%");
            });
        }
    
        if ($request->filled('game_id')) {
            $query->where('game_id', $request->game_id);
        }
    
        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }
    
        if ($request->filled('date_to')) {
            $query->whereDate('end_date', '<=', $request->date_to);
        }
    
        // Sorting (default: newest first)
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
    
        // Paginate
        $tournaments = $query->paginate(10);
    
        // Map results
        $response = $tournaments->map(function ($t) {
            // Get all registrations for the winning team (status=1)
            // For solo: match by user_id or name
            // For team: match by team_name
            $winnerRegistrations = $t->registrations
                ->where('status', 1)
                ->filter(function ($reg) use ($t) {
                    // If it's a solo registration
                    if ($reg->type === 'solo') {
                        // Check if the registration name matches winner_team_name
                        // OR if the user's full name matches
                        $user = $reg->user;
                        $userFullName = $user ? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) : '';
                        $regName = $reg->name ?? '';
                        
                        return $regName === $t->winner_team_name || 
                               $userFullName === $t->winner_team_name ||
                               $user->username === $t->winner_team_name;
                    }
                    // For team registrations
                    return $reg->team_name === $t->winner_team_name;
                });
    
            // If no registrations found with the above logic, try to get all registrations
            // This is a fallback for solo registrations
            if ($winnerRegistrations->isEmpty()) {
                $winnerRegistrations = $t->registrations
                    ->where('status', 1)
                    ->filter(function ($reg) use ($t) {
                        // For solo, the winner_team_name might be the user's name
                        if ($reg->type === 'solo') {
                            $user = $reg->user;
                            if ($user) {
                                $fullName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
                                return $fullName === $t->winner_team_name || 
                                       $user->username === $t->winner_team_name;
                            }
                            return $reg->name === $t->winner_team_name;
                        }
                        return $reg->team_name === $t->winner_team_name;
                    });
            }
    
            // ----- Determine team type from first registration -----
            $firstWinnerReg = $winnerRegistrations->first();
            $teamType = $firstWinnerReg ? $firstWinnerReg->type : null;
    
            // ----- Members list with prize details -----
            $members = collect();
            
            if ($teamType === 'solo') {
                // For solo: each registration is a separate player
                $members = $winnerRegistrations->map(function ($reg) {
                    $user = $reg->user;
                    
                    // Handle prize_distributed_at
                    $prizeDistributedAt = null;
                    if ($reg->prize_distributed_at) {
                        if ($reg->prize_distributed_at instanceof \Carbon\Carbon) {
                            $prizeDistributedAt = $reg->prize_distributed_at->toDateTimeString();
                        } else {
                            $prizeDistributedAt = (string) $reg->prize_distributed_at;
                        }
                    }
    
                    // If user exists, use user data
                    if ($user) {
                        return [
                            'id'                    => $user->id,
                            'first_name'            => $user->first_name,
                            'last_name'             => $user->last_name,
                            'username'              => $user->username,
                            'is_captain'            => true, // Solo player is always captain
                            'prize_amount'          => $reg->prize_amount ? (float) $reg->prize_amount : 0,
                            'prize_rank'            => $reg->prize_rank ?? '1',
                            'prize_distributed_at'  => $prizeDistributedAt,
                        ];
                    } else {
                        // Fallback: use registration data
                        return [
                            'id'                    => $reg->user_id ?? null,
                            'first_name'            => $reg->name ?? 'Player',
                            'last_name'             => null,
                            'username'              => $reg->name ?? 'Player',
                            'is_captain'            => true,
                            'prize_amount'          => $reg->prize_amount ? (float) $reg->prize_amount : 0,
                            'prize_rank'            => $reg->prize_rank ?? '1',
                            'prize_distributed_at'  => $prizeDistributedAt,
                        ];
                    }
                })->filter()->values();
            } else {
                // For team: all members in one registration
                $members = $winnerRegistrations->map(function ($reg) {
                    $user = $reg->user;
                    if (!$user) {
                        return null;
                    }
    
                    // Handle prize_distributed_at properly
                    $prizeDistributedAt = null;
                    if ($reg->prize_distributed_at) {
                        if ($reg->prize_distributed_at instanceof \Carbon\Carbon) {
                            $prizeDistributedAt = $reg->prize_distributed_at->toDateTimeString();
                        } else {
                            $prizeDistributedAt = (string) $reg->prize_distributed_at;
                        }
                    }
    
                    return [
                        'id'                    => $user->id,
                        'first_name'            => $user->first_name,
                        'last_name'             => $user->last_name,
                        'username'              => $user->username,
                        'is_captain'            => (bool) $reg->is_captain,
                        'prize_amount'          => $reg->prize_amount ? (float) $reg->prize_amount : 0,
                        'prize_rank'            => $reg->prize_rank,
                        'prize_distributed_at'  => $prizeDistributedAt,
                    ];
                })->filter()->values();
            }
    
            // ----- Find the captain -----
            $captainReg = null;
            if ($teamType === 'solo') {
                // For solo, the first registration is the captain
                $captainReg = $winnerRegistrations->first();
            } else {
                // For team, find the one with is_captain = 1
                $captainReg = $winnerRegistrations->firstWhere('is_captain', 1);
                // If no captain found, use the first registration
                if (!$captainReg) {
                    $captainReg = $winnerRegistrations->first();
                }
            }
    
            $captainData = null;
            if ($captainReg) {
                $captainUser = $captainReg->user;
                
                if ($captainUser) {
                    $captainData = [
                        'id'            => $captainUser->id,
                        'first_name'    => $captainUser->first_name,
                        'last_name'     => $captainUser->last_name,
                        'username'      => $captainUser->username,
                        'profile_image' => $this->getUserProfileImage($captainUser->id),
                        'social_links'  => $this->getUserSocialLinks($captainUser->id),
                        'prize_amount'  => $captainReg->prize_amount ? (float) $captainReg->prize_amount : 0,
                        'prize_rank'    => $captainReg->prize_rank,
                    ];
                } else {
                    // Fallback when user not found
                    $captainData = [
                        'id'            => $captainReg->user_id ?? null,
                        'first_name'    => $captainReg->name ?? $captainReg->team_name ?? 'Player',
                        'last_name'     => null,
                        'username'      => $captainReg->name ?? $captainReg->team_name ?? 'Player',
                        'profile_image' => null,
                        'social_links'  => null,
                        'prize_amount'  => $captainReg->prize_amount ? (float) $captainReg->prize_amount : 0,
                        'prize_rank'    => $captainReg->prize_rank ?? '1',
                    ];
                }
            }
    
            // ----- Tournament logo (absolute URL) -----
            $tournamentLogo = $t->logo ? Storage::disk('public')->url($t->logo) : null;
    
            // ----- Team type and team logo (absolute URL) -----
            $teamLogo = $firstWinnerReg && $firstWinnerReg->team_logo
                ? Storage::disk('public')->url($firstWinnerReg->team_logo)
                : null;
    
            // ----- Calculate total distributed and remaining -----
            $totalDistributed = $winnerRegistrations->sum('prize_amount') ?? 0;
            $totalPrizePool = $t->prize_pool ?? 0;
            $remainingPrize = $totalPrizePool - $totalDistributed;
    
            // ----- Prize distribution summary -----
            $prizeDistribution = [
                'total_prize_pool' => (float) $totalPrizePool,
                'total_distributed' => (float) $totalDistributed,
                'remaining' => (float) $remainingPrize,
                'is_fully_distributed' => $remainingPrize <= 0,
                'distributed_to' => $winnerRegistrations->where('prize_amount', '>', 0)->count(),
                'total_members' => $winnerRegistrations->count(),
            ];
    
            return [
                'id'    => $t->id,
                'title' => $t->title,
                'game'  => $t->game ? $t->game->name : null,
                'date'  => $t->end_date ?? $t->start_date,
                'total_participants' => $t->registrations_count,
                'logo'   => $tournamentLogo,
                'winner' => [
                    'team_name' => $t->winner_team_name,
                    'prize_pool' => (float) $t->prize_pool ?? 0,
                    'team_type' => $teamType,
                    'team_logo' => $teamLogo,
                    'captain'   => $captainData,
                    'members'   => $members,
                    'prize_distribution' => $prizeDistribution,
                ],
            ];
        });
    
        return response()->json([
            'data' => $response,
            'meta' => [
                'current_page' => $tournaments->currentPage(),
                'last_page'    => $tournaments->lastPage(),
                'total'        => $tournaments->total(),
            ],
        ]);
    }

}
