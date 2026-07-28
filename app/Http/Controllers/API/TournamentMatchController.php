<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\Matchs;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Models\TournamentRegistration;

use Exception;

class TournamentMatchController extends Controller
{
    /**
     * 4. List Matches
     * Show all matches as per admin-maintained bracket
     */
    // public function listMatches($tournamentId)
    // {
    //     try {
    //         $tournament = Tournament::findOrFail($tournamentId);

    //         $matches = Matchs::with(['team1', 'team2', 'winner'])
    //             ->where('tournament_id', $tournamentId)
    //             ->orderByRaw("FIELD(round,'Round 1','Quarterfinal','Semifinal','Final')")
    //             ->orderBy('match_order')
    //             ->get();

    //         return response()->json([
    //             'success' => true,
    //             'data' => [
    //                 'tournament' => $tournament,
    //                 'matches' => $matches
    //             ]
    //         ], 200);

    //     } catch (ModelNotFoundException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Tournament not found'
    //         ], 200);

    //     } catch (Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to fetch matches',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
    
    public function allmatchDetails(Request $request, $tournamentId)
{
    try {

        /* ------------------------------------
        | 1️⃣ Tournament Details
        ------------------------------------ */
        $tournament = Tournament::findOrFail($tournamentId);
        $now = now();

        // UPDATED STATUS LOGIC
        if ($tournament->winner_team_id != null) {
            $tournament_status = 'completed';
        } 
        elseif ($tournament->end_date && $tournament->end_date < $now) {
            $tournament_status = 'completed';
        } 
        elseif ($tournament->start_date > $now) {
            $tournament_status = 'upcoming';
        } 
        else {
            $tournament_status = 'ongoing';
        }

        /* ------------------------------------
        | 2️⃣ Tournament Teams
        ------------------------------------ */
        $registrations = TournamentRegistration::with('user.profile')
            ->where('type', 'team')
            ->where('tournament_id', $tournamentId)
            ->where('status', 1)
            ->get()
            ->groupBy(function ($item) {
                return strtolower(trim($item->team_name));
            });

        $teams_count = $registrations->count();

        /* ------------------------------------
        | 3️⃣ Team Builder
        ------------------------------------ */
        $buildTeam = function ($teamName) use ($registrations) {

            if (!$teamName) {
                return null;
            }

            $teamName = strtolower(trim($teamName));

            if (!$registrations->has($teamName)) {
                return null;
            }

            $members = $registrations[$teamName];
            $team = $members->first();

            return [
                'team_id'   => $team->id,
                'team_name' => $team->team_name,
                'team_tag'  => $team->team_tag,

                'team_logo' => $team->team_logo
                    ? asset('storage/' . $team->team_logo)
                    : null,

                'team_size' => $members->count(),

                'members' => $members->map(function ($member) {

                    return [
                        'user_id' => $member->user->id ?? null,
                        'name' => trim(($member->user->first_name ?? '') . ' ' . ($member->user->last_name ?? '')),
                        'email' => $member->user->email ?? null,
                        'phone' => $member->user->mobile ?? null,

                        'profile_image' => optional($member->user->profile)->profile_image
                            ? asset('storage/' . $member->user->profile->profile_image)
                            : null,
                    ];

                })->values(),
            ];
        };

        /* ------------------------------------
        | 4️⃣ Get Matches
        ------------------------------------ */
        $matches = Matchs::where('tournament_id', $tournamentId)
            ->orderBy('match_order')
            ->get();

        /* ------------------------------------
        | 5️⃣ Group Matches by Round
        ------------------------------------ */
        $grouped = $matches->groupBy('round');

        /* ------------------------------------
        | 6️⃣ Custom Round Order
        ------------------------------------ */
        $roundOrder = [
            'Round 1',
            'Quarterfinal',
            'Semifinal',
            'Final'
        ];

        $sortedRounds = [];

        foreach ($roundOrder as $round) {

            if (!isset($grouped[$round])) {
                continue;
            }

            $sortedRounds[$round] = $grouped[$round]->map(function ($match) use ($buildTeam) {

                return [
                    'match_id'   => $match->id,
                    'match_date' => $match->match_date,
                    'match_time' => $match->match_time,
                    'status'     => $match->status,
                    'played_at'  => $match->played_at,

                    'team_vs' => [
                        'team1' => $buildTeam($match->team1_name),
                        'team2' => $buildTeam($match->team2_name),
                    ],

                    'winner' => $match->winner_team_name
                        ? $buildTeam($match->winner_team_name)
                        : null,

                    'banner' => $match->banner
                        ? asset('storage/' . $match->banner)
                        : null,

                    'match_video' => $match->match_video,
                ];

            })->values();
        }

        /* ------------------------------------
        | 7️⃣ Final Response
        ------------------------------------ */
        return response()->json([

            'status' => true,

            'tournament' => [
                'id'          => $tournament->id,
                'name'        => $tournament->title,
                'status'      => $tournament_status,
                'prize_pool'  => $tournament->prize_pool,
                'teams_count' => $teams_count
            ],

            'matches' => $sortedRounds

        ]);

    } catch (\Throwable $e) {

        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ], 500);

    }
}
    
//     public function allmatchDetails(Request $request, $tournamentId)
// {
//     try {

//         /* ------------------------------------
//         | 1️⃣ Tournament Details
//         ------------------------------------ */
//         $tournament = Tournament::findOrFail($tournamentId);
//         $now = now();

//         if ($tournament->start_date > $now) {
//             $tournament_status = 'upcoming';
//         } elseif ($tournament->end_date && $tournament->end_date < $now) {
//             $tournament_status = 'completed';
//         } else {
//             $tournament_status = 'ongoing';
//         }

//         /* ------------------------------------
//         | 2️⃣ Tournament Teams
//         ------------------------------------ */
//         $registrations = TournamentRegistration::with('user.profile')
//             ->where('type', 'team')
//             ->where('tournament_id', $tournamentId)
//             ->where('status',1)
//             ->get()
//             ->groupBy(function ($item) {
//                 return strtolower(trim($item->team_name));
//             });

//         $teams_count = $registrations->count();

//         /* ------------------------------------
//         | 3️⃣ Team Builder
//         ------------------------------------ */
//         $buildTeam = function ($teamName) use ($registrations) {

//             if (!$teamName) {
//                 return null;
//             }

//             $teamName = strtolower(trim($teamName));

//             if (!$registrations->has($teamName)) {
//                 return null;
//             }

//             $members = $registrations[$teamName];
//             $team = $members->first();

//             return [
//                 'team_id' => $team->id,
//                 'team_name' => $team->team_name,
//                 'team_tag' => $team->team_tag,

//                 'team_logo' => $team->team_logo
//                     ? asset('storage/' . $team->team_logo)
//                     : null,

//                 'team_size' => $members->count(),

//                 'members' => $members->map(function ($member) {

//                     return [
//                         'user_id' => $member->user->id ?? null,
//                         'name' => trim(($member->user->first_name ?? '') . ' ' . ($member->user->last_name ?? '')),
//                         'email' => $member->user->email ?? null,
//                         'phone' => $member->user->mobile ?? null,

//                         'profile_image' => optional($member->user->profile)->profile_image
//                             ? asset('storage/' . $member->user->profile->profile_image)
//                             : null,
//                     ];

//                 })->values(),
//             ];
//         };

//         /* ------------------------------------
//         | 4️⃣ Get Matches
//         ------------------------------------ */
//         $matches = Matchs::where('tournament_id', $tournamentId)
//             ->orderBy('match_order')
//             ->get();

//         /* ------------------------------------
//         | 5️⃣ Group Matches by Round
//         ------------------------------------ */
//         $grouped = $matches->groupBy('round');

//         /* ------------------------------------
//         | 6️⃣ Custom Round Order
//         ------------------------------------ */
//         $roundOrder = [
//             'Round 1',
//             'Quarterfinal',
//             'Semifinal',
//             'Final'
//         ];

//         $sortedRounds = [];

//         foreach ($roundOrder as $round) {

//             if (!isset($grouped[$round])) {
//                 continue;
//             }

//             $sortedRounds[$round] = $grouped[$round]->map(function ($match) use ($buildTeam) {

//                 return [
//                     'match_id' => $match->id,
//                     'match_date' => $match->match_date,
//                     'match_time' => $match->match_time,
//                     'status' => $match->status,
//                     'played_at' => $match->played_at,

//                     'team_vs' => [
//                         'team1' => $buildTeam($match->team1_name),
//                         'team2' => $buildTeam($match->team2_name),
//                     ],

//                     'winner' => $match->winner_team_name
//                         ? $buildTeam($match->winner_team_name)
//                         : null,

//                     'banner' => $match->banner
//                         ? asset('storage/' . $match->banner)
//                         : null,

//                     'match_video' => $match->match_video,
//                 ];

//             })->values();
//         }

//         /* ------------------------------------
//         | 7️⃣ Final Response
//         ------------------------------------ */
//         return response()->json([

//             'status' => true,

//             'tournament' => [
//                 'id' => $tournament->id,
//                 'name' => $tournament->title,
//                 'status' => $tournament_status,
//                 'prize_pool' => $tournament->prize_pool,
//                 'teams_count' => $teams_count
//             ],

//             'matches' => $sortedRounds

//         ]);

//     } catch (\Throwable $e) {

//         return response()->json([
//             'status' => false,
//             'message' => $e->getMessage()
//         ], 500);

//     }
// }


    public function listMatches($tournamentId)
    {
        try {
            $baseUrl = 'https://www.markupdesigns.net/qatar-esports/storage';

            $tournament = Tournament::findOrFail($tournamentId);

            $matches = Matchs::with(['team1', 'team2', 'winner'])
                ->where('tournament_id', $tournamentId)
                ->orderByRaw("FIELD(round,'Round 1','Quarterfinal','Semifinal','Final')")
                ->orderBy('match_order')
                ->get()
                ->map(function ($match) use ($baseUrl) {
                    if ($match->banner) {
                        $match->banner_url = $baseUrl . '/' . ltrim($match->banner, '/');
                    } else {
                        $match->banner_url = null;
                    }
                    return $match;
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'tournament' => $tournament,
                    'matches' => $matches
                ]
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tournament not found'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch matches',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function matchDetails($tournamentId, $matchId)
    {
        $match = Matchs::with(['team1', 'team2', 'winner'])
            ->where('tournament_id', $tournamentId)
            ->where('id', $matchId)
            ->first();

        if (!$match) {
            return response()->json([
                'success' => false,
                'message' => 'Match not found for this tournament'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'matches' => [
                $match
            ]
        ], 200);
    }


    /**
     * 5. Map Veto Status
     * Show current map ban/pick for a match
     */
    public function mapVetoStatus($tournamentId, $matchId)
    {
        try {
            $match = Matchs::with(['team1', 'team2'])
                ->where('tournament_id', $tournamentId)
                ->findOrFail($matchId);

            return response()->json([
                'success' => true,
                'data' => [
                    'match_id' => $match->id,
                    'map_veto' => $match->maps ? json_decode($match->maps, true) : [
                        'ban' => [],
                        'pick' => []
                    ]
                ]
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Match not found'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch map veto status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 6. Submit Map Ban/Pick
     */
    public function submitMapVeto(Request $request, $tournamentId, $matchId)
    {
        try {
            $validated = $request->validate([
                'team_id' => 'required|exists:tournament_registrations,id',
                'action'  => 'required|in:ban,pick',
                'map'     => 'required|string'
            ]);

            $match = Matchs::where('tournament_id', $tournamentId)
                ->findOrFail($matchId);

            $maps = $match->maps
                ? json_decode($match->maps, true)
                : ['ban' => [], 'pick' => []];

            // Prevent duplicate entries
            foreach ($maps[$validated['action']] as $entry) {
                if ($entry['map'] === $validated['map']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Map already ' . $validated['action'] . 'ned'
                    ], 409);
                }
            }

            $maps[$validated['action']][] = [
                'team_id' => $validated['team_id'],
                'map' => $validated['map']
            ];

            $match->maps = json_encode($maps);
            $match->save();

            return response()->json([
                'success' => true,
                'message' => ucfirst($validated['action']) . ' submitted successfully',
                'data' => $maps
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Match not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit map veto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 8. Full Bracket API
     * Show round-wise single-elimination bracket
     */


    // public function fullBracket($tournamentId)
    // {
    //     try {
    //         $tournament = Tournament::findOrFail($tournamentId);

    //         $rounds = ['Round 1', 'Quarterfinal', 'Semifinal', 'Final'];
    //         $bracket = [];

    //         foreach ($rounds as $round) {
    //             $matches = Matchs::with(['team1', 'team2', 'winner'])
    //                 ->where('tournament_id', $tournamentId)
    //                 ->where('round', $round)
    //                 ->orderBy('match_order')
    //                 ->get();

    //             if ($matches->isNotEmpty()) {
    //                 $bracket[$round] = $matches;
    //             }
    //         }

    //         // ✅ Winner team as ARRAY inside bracket
    //         $bracket['winner_team'] = [];

    //         if ($tournament->winner_team_id) {
    //             $team = TournamentRegistration::find($tournament->winner_team_id);
    //             if ($team) {
    //                 $bracket['winner_team'][] = $team;
    //             }
    //         }

    //         return response()->json([
    //             'success' => true,
    //             'data' => [
    //                 'tournament' => $tournament,
    //                 'bracket'    => $bracket
    //             ]
    //         ], 200);

    //     } catch (ModelNotFoundException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Tournament not found'
    //         ], 404);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to load tournament bracket',
    //             'error'   => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function fullBracket($tournamentId)
    {
        try {
            $tournament = Tournament::findOrFail($tournamentId);

            $rounds = ['Round 1', 'Quarterfinal', 'Semifinal', 'Final'];
            $bracket = [];

            foreach ($rounds as $round) {
                $matches = Matchs::with(['team1', 'team2', 'winner'])
                    ->where('tournament_id', $tournamentId)
                    ->where('round', $round)
                    ->orderBy('match_order')
                    ->get();

                if ($matches->isNotEmpty()) {
                    $bracket[$round] = $matches;
                }
            }

            /**
             * ✅ Add winner_team ONLY if winner exists
             */
            if ($tournament->winner_team_id) {
                $team = TournamentRegistration::find($tournament->winner_team_id);

                if ($team) {
                    $bracket['winner_team'] = [$team];
                }
            }

            /**
             * ❌ If no rounds AND no winner → No data found
             */
            if (empty($bracket)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data found'
                ], 200);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'tournament' => $tournament,
                    'bracket'    => $bracket
                ]
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tournament not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load tournament bracket',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
