<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\TournamentRegistration;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TournamentRegistrationController extends Controller
{
    /**
     * Solo Registration
     * POST /api/tournaments/{id}/register/solo
     */
    public function soloRegister(Request $request, $id)
    {
        $user = $request->user();
        $tournament = Tournament::findOrFail($id);

        // if (!$tournament->is_registration_open) {
        //     return response()->json(['message' => 'Registration is closed'], 422);
        // }
        
        if (Carbon::parse($tournament->registration_end)->lt(Carbon::now())) {
            return response()->json([
                'message' => 'Registration is closed'
            ], 422);
        }

        if ($tournament->max_participants && $tournament->registered_participants >= $tournament->max_participants) {
            return response()->json(['message' => 'Tournament is full'], 422);
        }

        $exists = TournamentRegistration::where('tournament_id', $tournament->id)
            ->where('user_id', $user->id)
            ->where('type', 'solo')
            ->first();

        if ($exists) {
            return response()->json(['message' => 'Already registered'], 200);
        }

        $registration = TournamentRegistration::create([
            'tournament_id' => $tournament->id,
            'type' => 'solo',
            'name' => $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
            'phone' => $user->mobile ?? $user->phone,
            'user_id' => $user->id,
        ]);

        $tournament->increment('registered_participants');

        return response()->json([
            'message' => 'Successfully registered for solo',
            'registration' => $registration
        ]);
    }

    /**
     * Team Registration (Create Team)
     * POST /api/tournaments/{id}/register/team
     */
    public function teamRegister(Request $request, $id)
    {
        $user = $request->user();

        $request->validate([
            'team_name' => 'required|string|max:191',
            'team_tag' => 'required|string|max:50',
            'team_logo' => 'nullable|image|max:2048',
        ]);
    
        $tournament = Tournament::findOrFail($id);
        // if (!$tournament->is_registration_open) {
        //     return response()->json(['message' => 'Registration is closed'], 422);
        // }
        
        if (Carbon::parse($tournament->registration_end)->lt(Carbon::now())) {
            return response()->json([
                'message' => 'Registration is closed'
            ], 422);
        }

        // Duplicate team check
        $duplicate = TournamentRegistration::where('tournament_id', $tournament->id)
            ->where('type', 'team')
            ->where(function ($q) use ($request) {
                $q->where('team_name', $request->team_name)
                  ->orWhere('team_tag', $request->team_tag);
            })
            ->exists();

        if ($duplicate) {
            return response()->json(['message' => 'Team name or tag already exists'], 200);
        }

        // Upload team logo
        $teamLogoPath = null;
        if ($request->hasFile('team_logo')) {
            $teamLogoPath = $request->file('team_logo')->store('teams', 'public');
        }

        // Generate unique invite code
        do {
            $inviteCode = Str::random(16);
        } while (TournamentRegistration::where('invite_link', $inviteCode)->exists());

        $registration = TournamentRegistration::create([
            'tournament_id' => $tournament->id,
            'type' => 'team',
            'team_name' => $request->team_name,
            'team_tag' => $request->team_tag,
            'team_logo' => $teamLogoPath,
            'is_captain' => true,
            'invite_link' => $inviteCode,
            'name' => $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
            'phone' => $user->mobile ?? $user->phone,
            'user_id' => $user->id,
        ]);

        $tournament->increment('registered_participants');

        // Frontend invite URL
        //$tournamentSlug = Str::slug($tournament->title);
        $tournamentTitle = rawurlencode($tournament->title);
       // $inviteUrl = 'https://www.markupdesigns.net/qec-web/tourmainpage/'
        $inviteUrl = 'http://localhost:5173/qec-web/tourmainpage/'
            . $tournamentTitle
            . '?invite=' . $inviteCode;

        return response()->json([
            'message' => 'Team created successfully',
            'registration' => $registration,
            'invite_link' => $inviteUrl,
            'team_logo_url' => $teamLogoPath ? asset('storage/' . $teamLogoPath) : null,
        ]);
    }

    /**
     * Generate Invite Link (Existing Team)
     */
    public function generateInviteLink(Request $request, $id)
    {
        $user = $request->user();

        $registration = TournamentRegistration::where('tournament_id', $id)
            ->where('user_id', $user->id)
            ->where('type', 'team')
            ->firstOrFail();

        if (!$registration->invite_link) {
            do {
                $registration->invite_link = Str::random(16);
            } while (TournamentRegistration::where('invite_link', $registration->invite_link)->exists());

            $registration->save();
        }

        $tournament = Tournament::findOrFail($registration->tournament_id);
        //$tournamentSlug = Str::slug($tournament->title);
        $tournamentTitle = rawurlencode($tournament->title);

        //$inviteUrl = 'https://www.markupdesigns.net/qec-web/tourmainpage/'
        $inviteUrl = 'http://localhost:5173/qec-web/tourmainpage/'
            . $tournamentTitle
            . '?invite=' . $registration->invite_link;

        return response()->json([
            'invite_link' => $inviteUrl
        ]);
    }

    /**
     * Join Team via Invite Code
     * POST /api/tournaments/join-team
     */
    public function joinTeam(Request $request)
    {
        $user = $request->user();

        // Validate request
        $request->validate([
            'invite_link' => 'required|string',
        ]);

        $inviteLink = $request->invite_link;

        // Extract invite code if frontend sent full URL
        if (strpos($inviteLink, 'invite=') !== false) {
            parse_str(parse_url($inviteLink, PHP_URL_QUERY), $queryParams);
            $inviteCode = $queryParams['invite'] ?? $inviteLink;
        } else {
            $inviteCode = $inviteLink;
        }

        // Find the team using the invite code stored in DB
        $team = TournamentRegistration::where('invite_link', $inviteCode)
            ->where('type', 'team')
            ->firstOrFail();

        // Get the tournament
        $tournament = Tournament::findOrFail($team->tournament_id);

        // Check if registration is open
        // if (!$tournament->is_registration_open) {
        //     return response()->json(['message' => 'Registration is closed'], 422);
        // }
        
        if (Carbon::parse($tournament->registration_end)->lt(Carbon::now())) {
            return response()->json([
                'message' => 'Registration is closed'
            ], 200);
        }

        // Check if user already joined this team
        $exists = TournamentRegistration::where('invite_link', $team->invite_link)
            ->where('user_id', $user->id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Already part of this team'], 200);
        }

        // Team size check
        $membersCount = TournamentRegistration::where('invite_link', $team->invite_link)->count();
        if ($tournament->team_size && $membersCount >= $tournament->team_size) {
            return response()->json(['message' => 'Team is full'], 422);
        }

        // Register the user to the team
        $registration = TournamentRegistration::create([
            'tournament_id' => $tournament->id,
            'type' => 'team',
            'team_name' => $team->team_name,
            'team_tag' => $team->team_tag,
            'team_logo' => $team->team_logo,
            'is_captain' => false,
            'invite_link' => $team->invite_link, // store invite code
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? null,
        ]);

        // Increment total registered participants
        $tournament->increment('registered_participants');

        // Build full invite URL to send back
        //$frontendBase = 'https://www.markupdesigns.net/qec-web/tourmainpage/';
        $frontendBase = 'http://localhost:5173/qec-web/tourmainpage/';
        $fullInviteUrl = $frontendBase . urlencode($tournament->name) . '?invite=' . $registration->invite_link;

        // Response
        return response()->json([
            'message' => 'Joined team successfully',
            'registration' => $registration,
            'invite_link' => $fullInviteUrl, // full URL for frontend
            'team_logo_url' => $team->team_logo ? asset('storage/' . $team->team_logo) : null,
        ]);
    }

    /**
     * My Tournaments API
     * GET /api/my-tournaments
     */


     public function myTournaments(Request $request)
    {
        try {
            $userId = auth()->id();

            $tournaments = Tournament::with(['registrations', 'game'])
                ->whereHas('registrations', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->orderByDesc('start_date')
                ->get();

            $response = $tournaments->map(function ($tournament) use ($userId) {

                // All registrations of this user for this tournament
                $userRegistrations = $tournament->registrations
                    ->where('user_id', $userId);

                // Map each registration individually
                return $userRegistrations->map(function ($registration) use ($tournament) {

                    $now = now();
                    if ($tournament->start_date > $now) {
                        $status = 'upcoming';
                    } elseif ($tournament->end_date && $tournament->end_date < $now) {
                        $status = 'completed';
                    } else {
                        $status = 'ongoing';
                    }

                    //$inviteUrl = 'https://www.markupdesigns.net/qec-web/tourmainpage/'
                    $inviteUrl = 'http://localhost:5173/qec-web/tourmainpage/'
                    . $tournament->title
                    . '?invite=' . $registration->invite_link;

                    return [
                        'tournament_id'   => $tournament->id,
                        'tournament_name' => $tournament->title,
                        'tournament_logo' => $tournament->logo
                            ? url('storage/' . $tournament->logo)
                            : null,

                        'game' => [
                            'id'   => $tournament->game->id ?? null,
                            'name' => $tournament->game->name ?? null,
                        ],

                        'prize'  => $tournament->prize_pool,
                        'date'   => [
                            'start' => $tournament->start_date,
                            'end'   => $tournament->end_date,
                        ],
                        'status' => $status,

                        'type'       => $registration->type,
                        'team_name'  => $registration->team_name,
                        'team_tag'   => $registration->team_tag,
                        'is_captain' => $registration->is_captain,
                        'invite_link'=> $inviteUrl,
                    ];
                });
            })->flatten(1); // Flatten because we have multiple registrations per tournament

            return response()->json([
                'status' => true,
                'data'   => $response
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * My Teams API
     * GET /api/my-teams
     */

    // public function myTeams(Request $request)
    // {
    //     try {
    //         $userId = $request->user()->id;

    //         // Get all team registrations (created or joined) by the user
    //         $teams = TournamentRegistration::with(['tournament.game'])
    //             ->where('type', 'team')
    //             ->where('user_id', $userId)
    //             ->get();

    //         $response = $teams->map(function ($team) {
    //             $tournament = $team->tournament;

    //             // Determine tournament status
    //             $now = now();
    //             if ($tournament->start_date > $now) {
    //                 $status = 'upcoming';
    //             } elseif ($tournament->end_date && $tournament->end_date < $now) {
    //                 $status = 'completed';
    //             } else {
    //                 $status = 'ongoing';
    //             }

    //             return [
    //                 'team_id'           => $team->id,
    //                 'team_name'         => $team->team_name,
    //                 'team_tag'          => $team->team_tag,
    //                 'is_captain'        => $team->is_captain,
    //                 'team_logo'         => $team->team_logo ? asset('storage/' . $team->team_logo) : null,

    //                 'tournament_id'     => $tournament->id,
    //                 'tournament_name'   => $tournament->title,
    //                 'tournament_logo'   => $tournament->logo ? asset('storage/' . $tournament->logo) : null,

    //                 'game' => [
    //                     'id'   => $tournament->game->id ?? null,
    //                     'name' => $tournament->game->name ?? null,
    //                 ],

    //                 'prize'             => $tournament->prize_pool,
    //                 'date'              => [
    //                     'start' => $tournament->start_date,
    //                     'end'   => $tournament->end_date,
    //                 ],
    //                 'status'            => $status,
    //                 'invite_link'       => $team->invite_link 
    //                     ? 'http://localhost:5173/qec-web/tourmainpage/' . rawurlencode($tournament->title) . '?invite=' . $team->invite_link 
    //                     : null,
    //             ];
    //         });

    //         return response()->json([
    //             'status' => true,
    //             'data'   => $response
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status'  => false,
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }
    
  public function myTeams(Request $request)
{
    try {
        $user = $request->user();
        $userId = $user->id;

        // Step 1: User ke saare teams
        $teams = TournamentRegistration::with(['tournament.game'])
            ->where('type', 'team')
            ->where('user_id', $userId)
            ->get();

        if ($teams->isEmpty()) {
            return response()->json([
                'status' => true,
                'data' => [
                    'user_id' => $userId,
                    'teams'   => []
                ]
            ]);
        }

        // Step 2: Unique team identifiers
        $teamKeys = $teams->map(function ($team) {
            return [
                'tournament_id' => $team->tournament_id,
                'team_name'     => $team->team_name,
            ];
        });

        // Step 3: All members of those teams (single query)
        $membersGrouped = TournamentRegistration::with(['user.profile'])
            ->where('type', 'team')
            ->where(function ($query) use ($teamKeys) {
                foreach ($teamKeys as $key) {
                    $query->orWhere(function ($q) use ($key) {
                        $q->where('tournament_id', $key['tournament_id'])
                          ->where('team_name', $key['team_name']);
                    });
                }
            })
            ->get()
            ->groupBy(function ($item) {
                return $item->tournament_id . '_' . $item->team_name;
            });

        // Step 4: Build response
        $teamsData = $teams->map(function ($team) use ($membersGrouped) {

            $tournament = $team->tournament;
            $groupKey   = $team->tournament_id . '_' . $team->team_name;
            $members    = $membersGrouped[$groupKey] ?? collect();

            // Tournament status
            $now = now();
            if ($tournament->start_date > $now) {
                $status = 'upcoming';
            } elseif ($tournament->end_date && $tournament->end_date < $now) {
                $status = 'completed';
            } else {
                $status = 'ongoing';
            }

            return [
                'team_id'   => $team->id,
                'team_name' => $team->team_name,
                'team_tag'  => $team->team_tag,
                'is_captain'=> $team->is_captain,
                'team_logo'=> $team->team_logo
                    ? asset('storage/' . $team->team_logo)
                    : null,

                // ✅ Team size
                'team_size' => $members->count(),

                // ✅ Team members
                'members' => $members->map(function ($member) {
                    return [
                        'user_id' => $member->user->id,
                        'name'    => trim($member->user->first_name . ' ' . $member->user->last_name),
                        'email'   => $member->user->email,
                        'phone'   => $member->user->mobile,
                        'profile_image' => optional($member->user->profile)->profile_image
                            ? asset('storage/' . $member->user->profile->profile_image)
                            : null,
                    ];
                }),

                // Tournament info
                'tournament' => [
                    'id'    => $tournament->id,
                    'name'  => $tournament->title,
                    'logo'  => $tournament->logo
                        ? asset('storage/' . $tournament->logo)
                        : null,
                    'game' => [
                        'id'   => $tournament->game->id ?? null,
                        'name' => $tournament->game->name ?? null,
                    ],
                    'prize' => $tournament->prize_pool,
                    'date' => [
                        'start' => $tournament->start_date,
                        'end'   => $tournament->end_date,
                    ],
                    'status' => $status,
                ],

                'invite_link' => $team->invite_link
                    ? 'http://localhost:5173/qec-web/tourmainpage/' .
                        rawurlencode($tournament->title) .
                        '?invite=' . $team->invite_link
                    : null,
            ];
        });

        return response()->json([
            'status' => true,
            // 'data' => [
                // 'user_id' => $userId,
                'teams'   => $teamsData
            // ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status'  => false,
            'message' => $e->getMessage()
        ], 500);
    }
}


public function myHistory(Request $request)
{
    try {
        $user   = $request->user();
        $userId = $user->id;

        // Step 1: All registrations of logged-in user
        $teams = TournamentRegistration::with(['tournament.game'])
            ->where('user_id', $userId)
            ->get();

        if ($teams->isEmpty()) {
            return response()->json([
                'status' => true,
                'history' => []
            ]);
        }

        // Step 2: Unique team identifiers
        $teamKeys = $teams->map(function ($team) {
            return [
                'tournament_id' => $team->tournament_id,
                'team_name'     => $team->team_name,
            ];
        });

        // Step 3: Team members of those teams
        $membersGrouped = TournamentRegistration::with(['user.profile'])
            ->where(function ($query) use ($teamKeys) {
                foreach ($teamKeys as $key) {
                    $query->orWhere(function ($q) use ($key) {
                        $q->where('tournament_id', $key['tournament_id'])
                            ->where('team_name', $key['team_name']);
                    });
                }
            })
            ->get()
            ->groupBy(function ($item) {
                return $item->tournament_id . '_' . $item->team_name;
            });

        // Step 4: Build response (ONLY completed)
        $historyData = $teams
            ->filter(function ($team) {
                // Tournament must be completed
                return $team->tournament->end_date && $team->tournament->end_date < now();
            })
            ->map(function ($team) use ($membersGrouped) {

                $tournament = $team->tournament;
                $groupKey   = $team->tournament_id . '_' . $team->team_name;
                $members    = $membersGrouped[$groupKey] ?? collect();

                return [
                    'team_id'    => $team->id,
                    'team_name'  => $team->team_name,
                    'team_tag'   => $team->team_tag,
                    'is_captain' => $team->is_captain,
                    'team_logo'  => $team->team_logo
                        ? asset('storage/' . $team->team_logo)
                        : null,

                    'team_size' => $members->count(),

                    'members' => $members->map(function ($member) {
                        return [
                            'user_id' => $member->user->id,
                            'name'    => trim($member->user->first_name . ' ' . $member->user->last_name),
                            'email'   => $member->user->email,
                            'phone'   => $member->user->mobile,
                            'profile_image' => optional($member->user->profile)->profile_image
                                ? asset('storage/' . $member->user->profile->profile_image)
                                : null,
                        ];
                    }),

                    'tournament' => [
                        'id'    => $tournament->id,
                        'name'  => $tournament->title,
                        'logo'  => $tournament->logo
                            ? asset('storage/' . $tournament->logo)
                            : null,
                        'game' => [
                            'id'   => $tournament->game->id ?? null,
                            'name' => $tournament->game->name ?? null,
                        ],
                        'prize' => $tournament->prize_pool,
                        'date' => [
                            'start' => $tournament->start_date,
                            'end'   => $tournament->end_date,
                        ],
                        'status' => 'completed', // Sirf completed show ho raha
                    ],

                    'invite_link' => $team->invite_link
                        ? 'http://localhost:5173/qec-web/tourmainpage/' .
                        rawurlencode($tournament->title) .
                        '?invite=' . $team->invite_link
                        : null,
                ];
            })
            ->values();

        return response()->json([
            'status' => true,
            'history' => $historyData
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status'  => false,
            'message' => $e->getMessage()
        ], 500);
    }
}


// public function myHistory(Request $request)
// {
//     try {
//         // Authenticated user
//         $user   = $request->user();
//         $userId = $user->id;

//         // Step 1: All registrations of logged-in user
//         $teams = TournamentRegistration::with(['tournament.game'])
//             ->where('user_id', $userId)
//             ->get();

//         if ($teams->isEmpty()) {
//             return response()->json([
//                 'status' => true,
//                 'history' => []
//             ]);
//         }

//         // Step 2: Unique team identifiers
//         $teamKeys = $teams->map(function ($team) {
//             return [
//                 'tournament_id' => $team->tournament_id,
//                 'team_name'     => $team->team_name,
//             ];
//         });

//         // Step 3: Team members of those teams
//         $membersGrouped = TournamentRegistration::with(['user.profile'])
//             ->where(function ($query) use ($teamKeys) {
//                 foreach ($teamKeys as $key) {
//                     $query->orWhere(function ($q) use ($key) {
//                         $q->where('tournament_id', $key['tournament_id'])
//                           ->where('team_name', $key['team_name']);
//                     });
//                 }
//             })
//             ->get()
//             ->groupBy(function ($item) {
//                 return $item->tournament_id . '_' . $item->team_name;
//             });

//         // Step 4: Build response (exclude upcoming)
//         $historyData = $teams
//             ->filter(function ($team) {
//                 return $team->tournament->start_date <= now();
//             })
//             ->map(function ($team) use ($membersGrouped) {

//                 $tournament = $team->tournament;
//                 $groupKey   = $team->tournament_id . '_' . $team->team_name;
//                 $members    = $membersGrouped[$groupKey] ?? collect();

//                 // Tournament status
//                 $now = now();
//                 if ($tournament->end_date && $tournament->end_date < $now) {
//                     $status = 'completed';
//                 } else {
//                     $status = 'ongoing';
//                 }

//                 return [
//                     'team_id'    => $team->id,
//                     'team_name'  => $team->team_name,
//                     'team_tag'   => $team->team_tag,
//                     'is_captain' => $team->is_captain,
//                     'team_logo'  => $team->team_logo
//                         ? asset('storage/' . $team->team_logo)
//                         : null,

//                     'team_size' => $members->count(),

//                     'members' => $members->map(function ($member) {
//                         return [
//                             'user_id' => $member->user->id,
//                             'name'    => trim($member->user->first_name . ' ' . $member->user->last_name),
//                             'email'   => $member->user->email,
//                             'phone'   => $member->user->mobile,
//                             'profile_image' => optional($member->user->profile)->profile_image
//                                 ? asset('storage/' . $member->user->profile->profile_image)
//                                 : null,
//                         ];
//                     }),

//                     'tournament' => [
//                         'id'    => $tournament->id,
//                         'name'  => $tournament->title,
//                         'logo'  => $tournament->logo
//                             ? asset('storage/' . $tournament->logo)
//                             : null,
//                         'game' => [
//                             'id'   => $tournament->game->id ?? null,
//                             'name' => $tournament->game->name ?? null,
//                         ],
//                         'prize' => $tournament->prize_pool,
//                         'date' => [
//                             'start' => $tournament->start_date,
//                             'end'   => $tournament->end_date,
//                         ],
//                         'status' => $status,
//                     ],

//                     'invite_link' => $team->invite_link
//                         ? 'http://localhost:5173/qec-web/tourmainpage/' .
//                             rawurlencode($tournament->title) .
//                             '?invite=' . $team->invite_link
//                         : null,
//                 ];
//             })
//             ->values();

//         return response()->json([
//             'status' => true,
//             'history' => $historyData
//         ]);

//     } catch (\Exception $e) {
//         return response()->json([
//             'status'  => false,
//             'message' => $e->getMessage()
//         ], 500);
//     }
// }



// public function teamMembers(Request $request)
// {
//     try {
//         $request->validate([
//             'tournament_id' => 'required|integer',
//             'team_name'     => 'required|string',
//         ]);

//         $tournamentId = $request->tournament_id;
//         $teamName     = $request->team_name;

//         // All members of ONE specific team
//         $members = TournamentRegistration::with(['user.profile'])
//             ->where('type', 'team')
//             ->where('tournament_id', $tournamentId)
//             ->where('team_name', $teamName)
//             ->get();

//         if ($members->isEmpty()) {
//             return response()->json([
//                 'status'  => false,
//                 'message' => 'Team not found or no members'
//             ], 404);
//         }
//         // dd($members);
//         return response()->json([
//             'status' => true,
//             'data' => [
//                 'tournament_id' => $tournamentId,
//                 'team_name'     => $teamName,
//                 'team_size'     => $members->count(),

//                 // ✅ Members detail
//                 'members' => $members->map(function ($member) {
//                     return [
//                         'user_id' => $member->user->id,
//                         'name'    => trim(
//                             $member->user->first_name . ' ' . $member->user->last_name
//                         ),
//                         'email'   => $member->user->email,
//                         'phone'   => $member->user->mobile,
//                         'profile_image' => optional($member->user->profile)->profile_image
//                             ? asset('storage/' . $member->user->profile->profile_image)
//                             : null,
//                         'is_captain' => $member->is_captain,
//                     ];
//                 }),
//             ]
//         ]);

//     } catch (\Exception $e) {
//         return response()->json([
//             'status'  => false,
//             'message' => $e->getMessage()
//         ], 500);
//     }
// }



}