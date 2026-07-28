<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Matchs;
use App\Models\MatchMap;
use App\Models\Map;
use App\Models\MatchMapVeto;
use App\Models\TeamRegistration;
use Illuminate\Support\Facades\DB;
use App\Models\TournamentRegistration;
use Illuminate\Support\Facades\Auth;
use App\Models\Tournament;

class MatchMapController extends Controller
{
    // public function mapveto(Request $request, $tournament_id)
    // {
    //     try {

    //         $user = Auth::user();

    //         if (!$user) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Unauthorized'
    //             ], 401);
    //         }

    //         $request->validate([
    //             'map_id' => 'required|exists:maps,id',
    //             'action' => 'required|in:ban,pick'
    //         ]);

    //         $tournament = Tournament::find($tournament_id);

    //         if (!$tournament) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Tournament not found'
    //             ]);
    //         }

    //         /*
    //     -----------------------------
    //     FIND TEAM
    //     -----------------------------
    //     */

    //         $team = TournamentRegistration::where('user_id', $user->id)
    //             ->where('tournament_id', $tournament_id)
    //             ->where('status', 1)
    //             ->first();

    //         if (!$team) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Please register first'
    //             ]);
    //         }

    //         /*
    //     -----------------------------
    //     FIND MATCH
    //     -----------------------------
    //     */

    //         $match = Matchs::where('tournament_id', $tournament_id)
    //             ->where(function ($q) use ($team) {
    //                 $q->where('team1_id', $team->id)
    //                     ->orWhere('team2_id', $team->id);
    //             })
    //             ->first();

    //         if (!$match) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Match not found'
    //             ]);
    //         }

    //         /*
    //     -----------------------------
    //     MAP VALIDATION
    //     -----------------------------
    //     */

    //         $map = Map::find($request->map_id);

    //         if ($map->game_id != $tournament->game_id) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Map does not belong to this tournament'
    //             ]);
    //         }

    //         /*
    //     -----------------------------
    //     CHECK BANNED MAPS
    //     -----------------------------
    //     */

    //         $bannedMaps = MatchMapVeto::where('match_id', $match->id)
    //             ->where('action', 'ban')
    //             ->pluck('map_id')
    //             ->toArray();

    //         if (in_array($request->map_id, $bannedMaps)) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'This map is banned'
    //             ]);
    //         }

    //         /*
    //     -----------------------------
    //     ❗ BAN VALIDATIONS
    //     -----------------------------
    //     */

    //         if ($request->action == 'ban') {

    //             $teamPickedMap = MatchMapVeto::where('match_id', $match->id)
    //                 ->where('team_id', $team->id)
    //                 ->where('action', 'pick')
    //                 ->exists();

    //             if (!$teamPickedMap) {

    //                 $totalMaps = Map::where('game_id', $tournament->game_id)->count();

    //                 $pickedMaps = MatchMapVeto::where('match_id', $match->id)
    //                     ->where('action', 'pick')
    //                     ->count();

    //                 $bannedCount = MatchMapVeto::where('match_id', $match->id)
    //                     ->where('action', 'ban')
    //                     ->count();

    //                 $remainingMaps = $totalMaps - ($pickedMaps + $bannedCount);

    //                 if ($remainingMaps <= 1) {
    //                     return response()->json([
    //                         'status' => false,
    //                         'message' => 'You must leave at least one map unbanned'
    //                     ]);
    //                 }
    //             }
    //             // Cannot ban picked map
    //             $alreadyPicked = MatchMapVeto::where('match_id', $match->id)
    //                 ->where('map_id', $request->map_id)
    //                 ->where('action', 'pick')
    //                 ->exists();

    //             if ($alreadyPicked) {
    //                 return response()->json([
    //                     'status' => false,
    //                     'message' => 'This map is already picked and cannot be banned'
    //                 ]);
    //             }

    //             /*
    //         -----------------------------
    //         ❗ TEAM CANNOT BAN ALL MAPS
    //         BUT allow ban if team already picked one map
    //         -----------------------------
    //         */
    //         }

    //         /*
    //     -----------------------------
    //     TEAM CANNOT PICK SAME MAP TWICE
    //     -----------------------------
    //     */

    //         if ($request->action == 'pick') {

    //             $alreadyPicked = MatchMapVeto::where('match_id', $match->id)
    //                 ->where('team_id', $team->id)
    //                 ->where('map_id', $request->map_id)
    //                 ->where('action', 'pick')
    //                 ->exists();

    //             if ($alreadyPicked) {
    //                 return response()->json([
    //                     'status' => false,
    //                     'message' => 'Your team already picked this map'
    //                 ]);
    //             }
    //         }

    //         /*
    //     -----------------------------
    //     SEQUENCE
    //     -----------------------------
    //     */

    //         $sequence = MatchMapVeto::where('match_id', $match->id)
    //             ->max('sequence_no');

    //         $sequence = $sequence ? $sequence + 1 : 1;

    //         DB::beginTransaction();

    //         /*
    //     -----------------------------
    //     SAVE BAN / PICK
    //     -----------------------------
    //     */

    //         MatchMapVeto::create([
    //             'match_id' => $match->id,
    //             'team_id' => $team->id,
    //             'map_id' => $request->map_id,
    //             'action' => $request->action,
    //             'sequence_no' => $sequence,
    //             'tournament_id' => $tournament_id
    //         ]);

    //         /*
    //     -----------------------------
    //     STORE PICK IN match_maps
    //     -----------------------------
    //     */

    //         if ($request->action == 'pick') {

    //             $order = DB::table('match_maps')
    //                 ->where('match_id', $match->id)
    //                 ->max('map_order');

    //             $order = $order ? $order + 1 : 1;

    //             DB::table('match_maps')->insert([
    //                 'match_id' => $match->id,
    //                 'map_id' => $request->map_id,
    //                 'map_order' => $order,
    //                 'created_at' => now(),
    //                 'updated_at' => now()
    //             ]);
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Map ' . $request->action . ' successful',
    //             'data' => [
    //                 'match_id' => $match->id,
    //                 'team_id' => $team->id,
    //                 'map_id' => $request->map_id,
    //                 'action' => $request->action,
    //                 'sequence_no' => $sequence
    //             ]
    //         ]);
    //     } catch (\Throwable $th) {

    //         DB::rollBack();

    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Failed to process veto',
    //             'error' => $th->getMessage()
    //         ], 500);
    //     }
    // }
    
    // public function mapveto(Request $request, $tournament_id)
    // {
    //     DB::beginTransaction();
    
    //     try {
    
    //         $user = Auth::user();
    
    //         if (!$user) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Unauthorized'
    //             ], 401);
    //         }
    
    //         $request->validate([
    //             'map_id' => 'required|exists:maps,id',
    //             'action' => 'required|in:ban,pick'
    //         ]);
    
    //         $tournament = Tournament::find($tournament_id);
    
    //         if (!$tournament) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Tournament not found'
    //             ]);
    //         }
    
    //         /*
    //         -----------------------------
    //         FIND TEAM
    //         -----------------------------
    //         */
    
    //         $team = TournamentRegistration::where('user_id', $user->id)
    //             ->where('tournament_id', $tournament_id)
    //             ->where('status', 1)
    //             ->first();
    
    //         if (!$team) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Please register first'
    //             ]);
    //         }
    
    //         /*
    //         -----------------------------
    //         FIND MATCH
    //         -----------------------------
    //         */
    
    //         $match = Matchs::where('tournament_id', $tournament_id)
    //             ->where(function ($q) use ($team) {
    //                 $q->where('team1_id', $team->id)
    //                   ->orWhere('team2_id', $team->id);
    //             })
    //             ->first();
    
    //         if (!$match) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Match not found'
    //             ]);
    //         }
    
    //         /*
    //         -----------------------------
    //         LOCK VETO ROWS (Race condition protection)
    //         -----------------------------
    //         */
    
    //         MatchMapVeto::where('match_id', $match->id)
    //             ->lockForUpdate()
    //             ->get();
    
    //         /*
    //         -----------------------------
    //         MAP VALIDATION
    //         -----------------------------
    //         */
    
    //         $map = Map::find($request->map_id);
    
    //         if (!$map || $map->game_id != $tournament->game_id) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Map does not belong to this tournament'
    //             ]);
    //         }
    
    //         /*
    //         -----------------------------
    //         TOTAL MAPS
    //         -----------------------------
    //         */
    
    //         $totalMaps = Map::where('game_id', $tournament->game_id)->count();
    
    //         $usedMapsCount = MatchMapVeto::where('match_id', $match->id)->count();
    
    //         /*
    //         -----------------------------
    //         VETO ALREADY FINISHED
    //         -----------------------------
    //         */
    
    //         if ($usedMapsCount >= $totalMaps) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Map veto already completed'
    //             ]);
    //         }
    
    //         /*
    //         -----------------------------
    //         MAP ALREADY USED
    //         -----------------------------
    //         */
    
    //         $mapUsed = MatchMapVeto::where('match_id', $match->id)
    //             ->where('map_id', $request->map_id)
    //             ->exists();
    
    //         if ($mapUsed) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'This map is already used'
    //             ]);
    //         }
    
    //         /*
    //         -----------------------------
    //         TURN VALIDATION
    //         -----------------------------
    //         */
    
    //         $lastMove = MatchMapVeto::where('match_id', $match->id)
    //             ->orderByDesc('sequence_no')
    //             ->first();
    
    //         if ($lastMove && $lastMove->team_id == $team->id) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Wait for opponent turn'
    //             ]);
    //         }
    
    //         /*
    //         -----------------------------
    //         LAST MAP BAN PROTECTION
    //         -----------------------------
    //         */
    
    //         if ($request->action == 'ban' && ($totalMaps - $usedMapsCount) <= 1) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Last map cannot be banned'
    //             ]);
    //         }
    
    //         /*
    //         -----------------------------
    //         SEQUENCE
    //         -----------------------------
    //         */
    
    //         $sequence = MatchMapVeto::where('match_id', $match->id)->max('sequence_no');
    //         $sequence = $sequence ? $sequence + 1 : 1;
    
    //         /*
    //         -----------------------------
    //         SAVE BAN / PICK
    //         -----------------------------
    //         */
    
    //         MatchMapVeto::create([
    //             'match_id' => $match->id,
    //             'team_id' => $team->id,
    //             'map_id' => $request->map_id,
    //             'action' => $request->action,
    //             'sequence_no' => $sequence,
    //             'tournament_id' => $tournament_id
    //         ]);
    
    //         /*
    //         -----------------------------
    //         STORE PICK
    //         -----------------------------
    //         */
    
    //         if ($request->action == 'pick') {
    
    //             $exists = DB::table('match_maps')
    //                 ->where('match_id', $match->id)
    //                 ->where('map_id', $request->map_id)
    //                 ->exists();
    
    //             if (!$exists) {
    
    //                 $order = DB::table('match_maps')
    //                     ->where('match_id', $match->id)
    //                     ->max('map_order');
    
    //                 $order = $order ? $order + 1 : 1;
    
    //                 DB::table('match_maps')->insert([
    //                     'match_id' => $match->id,
    //                     'map_id' => $request->map_id,
    //                     'map_order' => $order,
    //                     'created_at' => now(),
    //                     'updated_at' => now()
    //                 ]);
    //             }
    //         }
    
    //         /*
    //         -----------------------------
    //         AUTO DECIDER MAP
    //         -----------------------------
    //         */
    
    //         $usedMaps = MatchMapVeto::where('match_id', $match->id)->pluck('map_id');
    
    //         if ($totalMaps - $usedMaps->count() == 1) {
    
    //             $decider = Map::where('game_id', $tournament->game_id)
    //                 ->whereNotIn('id', $usedMaps)
    //                 ->first();
    
    //             if ($decider) {
    
    //                 $exists = DB::table('match_maps')
    //                     ->where('match_id', $match->id)
    //                     ->where('map_id', $decider->id)
    //                     ->exists();
    
    //                 if (!$exists) {
    
    //                     $order = DB::table('match_maps')
    //                         ->where('match_id', $match->id)
    //                         ->max('map_order');
    
    //                     $order = $order ? $order + 1 : 1;
    
    //                     DB::table('match_maps')->insert([
    //                         'match_id' => $match->id,
    //                         'map_id' => $decider->id,
    //                         'map_order' => $order,
    //                         'created_at' => now(),
    //                         'updated_at' => now()
    //                     ]);
    //                 }
    //             }
    //         }
    
    //         DB::commit();
    
    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Map ' . $request->action . ' successful',
    //             'data' => [
    //                 'match_id' => $match->id,
    //                 'team_id' => $team->id,
    //                 'map_id' => $request->map_id,
    //                 'action' => $request->action,
    //                 'sequence_no' => $sequence
    //             ]
    //         ]);
    
    //     } catch (\Throwable $th) {
    
    //         DB::rollBack();
    
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Failed to process veto',
    //             'error' => $th->getMessage()
    //         ], 500);
    //     }
    // }
    
    // public function mapveto(Request $request, $tournament_id)
    // {
    //     DB::beginTransaction();
    
    //     try {
    
    //         $user = Auth::user();
    
    //         if (!$user) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'You must be logged in to perform map veto.'
    //             ], 401);
    //         }
    
    //         $request->validate([
    //             'map_id' => 'required|exists:maps,id',
    //             'action' => 'required|in:ban,pick'
    //         ]);
    
    //         $tournament = Tournament::find($tournament_id);
    
    //         if (!$tournament) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Tournament not found.'
    //             ]);
    //         }
    
    //         /*
    //         FIND TEAM
    //         */
    
    //         $team = TournamentRegistration::where('user_id', $user->id)
    //             ->where('tournament_id', $tournament_id)
    //             ->where('status', 1)
    //             ->first();
    
    //         if (!$team) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'You are not registered in this tournament.'
    //             ]);
    //         }
    
    //         /*
    //         FIND MATCH
    //         */
    
    //         $match = Matchs::where('tournament_id', $tournament_id)
    //             ->where(function ($q) use ($team) {
    //                 $q->where('team1_id', $team->id)
    //                   ->orWhere('team2_id', $team->id);
    //             })
    //             ->first();
    
    //         if (!$match) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Match not found for your team.'
    //             ]);
    //         }
    
    //         /*
    //         BEST OF
    //         */
    
    //         $bestOf = $match->best_of ?? 1;
    
    //         if ($bestOf % 2 == 0) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Best Of must be an odd number (1,3,5).'
    //             ]);
    //         }
    
    //         /*
    //         MAP POOL
    //         */
    
    //         $allMaps = Map::where('game_id', $tournament->game_id)->pluck('id');
    //         $totalMaps = $allMaps->count();
    
    //         if ($totalMaps < $bestOf) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Map pool is too small for this Best Of format.'
    //             ]);
    //         }
    
    //         /*
    //         CALCULATIONS
    //         */
    
    //         $totalPicksRequired = $bestOf - 1;
    //         $totalBansRequired  = $totalMaps - $bestOf;
    //         $picksPerTeam       = floor($totalPicksRequired / 2);
    
    //         /*
    //         LOCK VETO ROWS
    //         */
    
    //         MatchMapVeto::where('match_id', $match->id)
    //             ->lockForUpdate()
    //             ->get();
    
    //         /*
    //         MAP VALIDATION
    //         */
    
    //         $map = Map::find($request->map_id);
    
    //         if (!$map || $map->game_id != $tournament->game_id) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Selected map does not belong to this tournament.'
    //             ]);
    //         }
    
    //         /*
    //         USED MAPS
    //         */
    
    //         $usedMaps = MatchMapVeto::where('match_id', $match->id)
    //             ->pluck('map_id');
    
    //         if ($usedMaps->contains($request->map_id)) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'This map has already been used in the veto.'
    //             ]);
    //         }
    
    //         /*
    //         TURN VALIDATION
    //         */
    
    //         $lastMove = MatchMapVeto::where('match_id', $match->id)
    //             ->orderByDesc('sequence_no')
    //             ->first();
    
    //         if ($lastMove && $lastMove->team_id == $team->id) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Please wait for the opposing team to take their turn.'
    //             ]);
    //         }
    
    //         /*
    //         CURRENT COUNTS
    //         */
    
    //         $totalPicks = MatchMapVeto::where('match_id', $match->id)
    //             ->where('action', 'pick')
    //             ->count();
    
    //         $totalBans = MatchMapVeto::where('match_id', $match->id)
    //             ->where('action', 'ban')
    //             ->count();
    
    //         /*
    //         BAN LIMIT
    //         */
    
    //         if ($request->action == 'ban' && $totalBans >= $totalBansRequired) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'All map bans are already completed. Please pick a map.'
    //             ]);
    //         }
    
    //         /*
    //         PICK LIMIT
    //         */
    
    //         if ($request->action == 'pick' && $totalPicks >= $totalPicksRequired) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'All required maps have already been picked.'
    //             ]);
    //         }
    
    //         /*
    //         TEAM PICK LIMIT
    //         */
    
    //         if ($request->action == 'pick') {
    
    //             $teamPickCount = MatchMapVeto::where('match_id', $match->id)
    //                 ->where('team_id', $team->id)
    //                 ->where('action', 'pick')
    //                 ->count();
    
    //             if ($teamPickCount >= $picksPerTeam) {
    //                 return response()->json([
    //                     'status' => false,
    //                     'message' => 'Your team has already picked the maximum allowed maps.'
    //                 ]);
    //             }
    //         }
    
    //         /*
    //         SAVE VETO
    //         */
    
    //         $sequence = MatchMapVeto::where('match_id', $match->id)
    //             ->max('sequence_no');
    
    //         $sequence = $sequence ? $sequence + 1 : 1;
    
    //         MatchMapVeto::create([
    //             'match_id' => $match->id,
    //             'team_id' => $team->id,
    //             'map_id' => $request->map_id,
    //             'action' => $request->action,
    //             'sequence_no' => $sequence,
    //             'tournament_id' => $tournament_id
    //         ]);
    
    //         /*
    //         STORE PICKED MAP
    //         */
    
    //         if ($request->action == 'pick') {
    
    //             $mapOrder = DB::table('match_maps')
    //                 ->where('match_id', $match->id)
    //                 ->max('map_order');
    
    //             $mapOrder = $mapOrder ? $mapOrder + 1 : 1;
    
    //             DB::table('match_maps')->insert([
    //                 'match_id' => $match->id,
    //                 'map_id' => $request->map_id,
    //                 'map_order' => $mapOrder,
    //                 'created_at' => now(),
    //                 'updated_at' => now()
    //             ]);
    //         }
    
    //         /*
    //         AUTO DECIDER
    //         */
    
    //         $remainingMaps = Map::where('game_id', $tournament->game_id)
    //             ->whereNotIn('id', MatchMapVeto::where('match_id', $match->id)->pluck('map_id'))
    //             ->get();
    
    //         if ($remainingMaps->count() == 1) {
    
    //             $decider = $remainingMaps->first();
    
    //             $exists = DB::table('match_maps')
    //                 ->where('match_id', $match->id)
    //                 ->where('map_id', $decider->id)
    //                 ->exists();
    
    //             if (!$exists) {
    
    //                 $mapOrder = DB::table('match_maps')
    //                     ->where('match_id', $match->id)
    //                     ->max('map_order');
    
    //                 $mapOrder = $mapOrder ? $mapOrder + 1 : 1;
    
    //                 DB::table('match_maps')->insert([
    //                     'match_id' => $match->id,
    //                     'map_id' => $decider->id,
    //                     'map_order' => $mapOrder,
    //                     'created_at' => now(),
    //                     'updated_at' => now()
    //                 ]);
    
    //                 DB::commit();
    
    //                 return response()->json([
    //                     'status' => true,
    //                     'message' => 'Map veto completed. "' . $decider->name . '" will be played as the decider map.'
    //                 ]);
    //             }
    //         }
    
    //         DB::commit();
    
    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Map ' . $request->action . ' recorded successfully.'
    //         ]);
    
    //     } catch (\Throwable $th) {
    
    //         DB::rollBack();
    
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Failed to process map veto.',
    //             'error' => $th->getMessage()
    //         ], 500);
    //     }
    // }
    public function mapveto(Request $request, $tournament_id)
    {
        DB::beginTransaction();
    
        try {
    
            $user = Auth::user();
    
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'You must be logged in to perform map veto.'
                ], 401);
            }
    
            $request->validate([
                'map_id' => 'required|exists:maps,id',
                'action' => 'required|in:ban,pick'
            ]);
    
            $tournament = Tournament::find($tournament_id);
    
            if (!$tournament) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tournament not found.'
                ]);
            }
    
            /*
            FIND TEAM
            */
    
            $team = TournamentRegistration::where('user_id', $user->id)
                ->where('tournament_id', $tournament_id)
                ->where('status', 1)
                ->first();
    
            if (!$team) {
                return response()->json([
                    'status' => false,
                    'message' => 'You are not registered in this tournament.'
                ]);
            }
    
            /*
            FIND MATCH
            */
    
            $match = Matchs::where('tournament_id', $tournament_id)
                ->where(function ($q) use ($team) {
                    $q->where('team1_id', $team->id)
                      ->orWhere('team2_id', $team->id);
                })
                ->first();
    
            if (!$match) {
                return response()->json([
                    'status' => false,
                    'message' => 'Match not found for your team.'
                ]);
            }
    
            /*
            BEST OF
            */
    
            $bestOf = $match->best_of ?? 1;
    
            if ($bestOf % 2 == 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Best Of must be an odd number (1,3,5).'
                ]);
            }
    
            /*
            MAP POOL
            */
    
            $allMaps = Map::where('game_id', $tournament->game_id)->pluck('id');
            $totalMaps = $allMaps->count();
    
            if ($totalMaps < $bestOf) {
                return response()->json([
                    'status' => false,
                    'message' => 'Map pool is too small for this Best Of format.'
                ]);
            }
    
            /*
            CALCULATIONS
            */
    
            $totalPicksRequired = $bestOf - 1;
            $totalBansRequired  = $totalMaps - $bestOf;
            $picksPerTeam       = floor($totalPicksRequired / 2);
    
            /*
            LOCK VETO ROWS
            */
    
            MatchMapVeto::where('match_id', $match->id)
                ->lockForUpdate()
                ->get();
    
            /*
            MAP VALIDATION
            */
    
            $map = Map::find($request->map_id);
    
            if (!$map || $map->game_id != $tournament->game_id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Selected map does not belong to this tournament.'
                ]);
            }
    
            /*
            USED MAPS
            */
    
            $usedMaps = MatchMapVeto::where('match_id', $match->id)
                ->pluck('map_id');
    
            if ($usedMaps->contains($request->map_id)) {
                return response()->json([
                    'status' => false,
                    'message' => 'This map has already been used in the veto.'
                ]);
            }
    
            /*
            CURRENT COUNTS
            */
    
            $totalPicks = MatchMapVeto::where('match_id', $match->id)
                ->where('action', 'pick')
                ->count();
    
            $totalBans = MatchMapVeto::where('match_id', $match->id)
                ->where('action', 'ban')
                ->count();
    
            /*
            CRITICAL CHANGE: ALL BANS MUST COMPLETE FIRST BEFORE PICKS START
            */
            
            // If total bans not completed yet, only ban action is allowed
            if ($totalBans < $totalBansRequired && $request->action == 'pick') {
                return response()->json([
                    'status' => false,
                    'message' => "All bans must be completed first. Remaining bans: " . ($totalBansRequired - $totalBans)
                ]);
            }
    
            // If all bans are completed, only pick action should be allowed
            if ($totalBans >= $totalBansRequired && $request->action == 'ban') {
                return response()->json([
                    'status' => false,
                    'message' => 'All bans have been completed. Now only picks are allowed.'
                ]);
            }
    
            /*
            TURN VALIDATION
            */
    
            $lastMove = MatchMapVeto::where('match_id', $match->id)
                ->orderByDesc('sequence_no')
                ->first();
    
            if ($lastMove && $lastMove->team_id == $team->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please wait for the opposing team to take their turn.'
                ]);
            }
    
            /*
            BAN LIMIT
            */
    
            if ($request->action == 'ban' && $totalBans >= $totalBansRequired) {
                return response()->json([
                    'status' => false,
                    'message' => 'All map bans are already completed. Please pick a map.'
                ]);
            }
    
            /*
            PICK LIMIT
            */
    
            if ($request->action == 'pick' && $totalPicks >= $totalPicksRequired) {
                return response()->json([
                    'status' => false,
                    'message' => 'All required maps have already been picked.'
                ]);
            }
    
            /*
            TEAM PICK LIMIT
            */
    
            if ($request->action == 'pick') {
    
                $teamPickCount = MatchMapVeto::where('match_id', $match->id)
                    ->where('team_id', $team->id)
                    ->where('action', 'pick')
                    ->count();
    
                if ($teamPickCount >= $picksPerTeam) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Your team has already picked the maximum allowed maps.'
                    ]);
                }
            }
    
            /*
            SAVE VETO
            */
    
            $sequence = MatchMapVeto::where('match_id', $match->id)
                ->max('sequence_no');
    
            $sequence = $sequence ? $sequence + 1 : 1;
    
            MatchMapVeto::create([
                'match_id' => $match->id,
                'team_id' => $team->id,
                'map_id' => $request->map_id,
                'action' => $request->action,
                'sequence_no' => $sequence,
                'tournament_id' => $tournament_id
            ]);
    
            /*
            STORE PICKED MAP
            */
    
            if ($request->action == 'pick') {
    
                $mapOrder = DB::table('match_maps')
                    ->where('match_id', $match->id)
                    ->max('map_order');
    
                $mapOrder = $mapOrder ? $mapOrder + 1 : 1;
    
                DB::table('match_maps')->insert([
                    'match_id' => $match->id,
                    'map_id' => $request->map_id,
                    'map_order' => $mapOrder,
                    'action' => 'pick',
                    'team1_side' => null,
                    'team2_side' => null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
    
            /*
            CHECK FOR AUTO DECIDER - AFTER SAVING CURRENT MOVE
            */
    
            // Get updated used maps after current move
            $updatedUsedMaps = MatchMapVeto::where('match_id', $match->id)->pluck('map_id');
            $remainingMaps = Map::where('game_id', $tournament->game_id)
                ->whereNotIn('id', $updatedUsedMaps)
                ->get();
            
            $remainingMapsCount = $remainingMaps->count();
    
            // If only one map remains, add it as decider
            if ($remainingMapsCount == 1) {
    
                $decider = $remainingMaps->first();
    
                $deciderExists = DB::table('match_maps')
                    ->where('match_id', $match->id)
                    ->where('map_id', $decider->id)
                    ->exists();
    
                if (!$deciderExists) {
    
                    $mapOrder = DB::table('match_maps')
                        ->where('match_id', $match->id)
                        ->max('map_order');
    
                    $mapOrder = $mapOrder ? $mapOrder + 1 : 1;
    
                    DB::table('match_maps')->insert([
                        'match_id' => $match->id,
                        'map_id' => $decider->id,
                        'map_order' => $mapOrder,
                        'action' => 'decider',
                        'team1_side' => null,
                        'team2_side' => null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
    
                    DB::commit();
    
                    // Determine response based on what action just happened
                    if ($request->action == 'pick') {
                        return response()->json([
                            'status' => true,
                            'message' => 'Map picked successfully. Also, decider map "' . $decider->name . '" has been added. Opposing team needs to select starting side for the picked map, and any team can select side for decider.',
                            'requires_side_selection' => true,
                            'data' => [
                                'match_id' => $match->id,
                                'picked_map' => [
                                    'map_id' => $request->map_id,
                                    'map_name' => $map->name,
                                    'action' => 'pick'
                                ],
                                'decider_map' => [
                                    'map_id' => $decider->id,
                                    'map_name' => $decider->name,
                                    'action' => 'decider'
                                ]
                            ]
                        ]);
                    } else {
                        // If last action was a ban and decider is added
                        return response()->json([
                            'status' => true,
                            'message' => 'Map veto completed. Decider map "' . $decider->name . '" has been added. Any team can now select starting side for the decider map.'
                        ]);
                    }
                }
            }
    
            DB::commit();
    
            // Regular response for normal ban/pick without decider
            if ($request->action == 'pick') {
                $opposingTeamId = ($match->team1_id == $team->id) ? $match->team2_id : $match->team1_id;
                $opposingTeam = TournamentRegistration::find($opposingTeamId);
                
                return response()->json([
                    'status' => true,
                    'message' => 'Map picked successfully. ' . ($opposingTeam->team_name ?? 'Opposing team') . ' now needs to select starting side.',
                    'requires_side_selection' => true,
                    'data' => [
                        'match_id' => $match->id,
                        'map_id' => $request->map_id,
                        'map_name' => $map->name,
                        'map_order' => DB::table('match_maps')->where('match_id', $match->id)->where('map_id', $request->map_id)->first()->map_order ?? null,
                        'action' => 'pick',
                        'picked_by_team' => $team->team_name,
                        'waiting_for_team' => $opposingTeam->team_name ?? 'Opposing team'
                    ]
                ]);
            }
    
            return response()->json([
                'status' => true,
                'message' => 'Map ' . $request->action . ' recorded successfully.'
            ]);
    
        } catch (\Throwable $th) {
    
            DB::rollBack();
    
            return response()->json([
                'status' => false,
                'message' => 'Failed to process map veto.',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    
    public function getMapVeto($tournament_id)
    {
        try {
    
            $user = Auth::user();
    
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }
    
            $team = TournamentRegistration::where('user_id', $user->id)
                ->where('tournament_id', $tournament_id)
                ->where('status', 1)
                ->first();
    
            if (!$team) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not registered in this tournament'
                ]);
            }
    
            $tournament = Tournament::find($tournament_id);
    
            if (!$tournament) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tournament not found'
                ]);
            }
    
            /*
            FIND MATCH
            */
    
            $match = Matchs::where('tournament_id', $tournament_id)
                ->where(function ($q) use ($team) {
                    $q->where('team1_id', $team->id)
                      ->orWhere('team2_id', $team->id);
                })
                ->first();
    
            if (!$match) {
                return response()->json([
                    'status' => false,
                    'message' => 'Match not found'
                ]);
            }
    
            /*
            VETO HISTORY
            */
    
            $vetoes = MatchMapVeto::where('match_id', $match->id)
                ->with('team:id,team_name')
                ->orderBy('sequence_no')
                ->get();
    
            /*
            FINAL MAPS (INCLUDING DECIDER)
            */
    
            $matchMaps = DB::table('match_maps')
                ->join('maps', 'maps.id', '=', 'match_maps.map_id')
                ->where('match_maps.match_id', $match->id)
                ->orderBy('map_order')
                ->select(
                    'match_maps.map_id',
                    'maps.name',
                    'match_maps.map_order'
                )
                ->get();
    
            /*
            DETECT DECIDER
            */
    
            $pickedMaps = $vetoes
                ->where('action', 'pick')
                ->pluck('map_id')
                ->toArray();
    
            $mapsData = $matchMaps->map(function ($map) use ($pickedMaps) {
    
                $type = in_array($map->map_id, $pickedMaps) ? 'pick' : 'decider';
    
                return [
                    'map_id' => $map->map_id,
                    'map_name' => $map->name,
                    'map_order' => $map->map_order,
                    'type' => $type
                ];
            });
    
            return response()->json([
                'status' => true,
                'message' => 'Map veto data fetched successfully',
                'data' => [
                    'veto_history' => $vetoes,
                    'match_maps' => $mapsData
                ]
            ]);
    
        } catch (\Throwable $th) {
    
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch map veto',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    
    // public function getMapVeto($tournament_id)
    // {
    //     try {
    
    //         $user = Auth::user();
    
    //         if (!$user) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Unauthorized'
    //             ], 401);
    //         }
    
    //         $team = TournamentRegistration::where('user_id', $user->id)
    //             ->where('tournament_id', $tournament_id)
    //             ->where('status', 1)
    //             ->first();
    
    //         if (!$team) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'User not registered in this tournament'
    //             ]);
    //         }
    
    //         $tournament = Tournament::find($tournament_id);
    
    //         if (!$tournament) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Tournament not found'
    //             ]);
    //         }
    
    //         /*
    //         FIND MATCH
    //         */
    
    //         $match = Matchs::where('tournament_id', $tournament_id)
    //             ->where(function ($q) use ($team) {
    //                 $q->where('team1_id', $team->id)
    //                   ->orWhere('team2_id', $team->id);
    //             })
    //             ->first();
    
    //         if (!$match) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Match not found'
    //             ]);
    //         }
    
    //         /*
    //         VETO HISTORY
    //         */
    
    //         $vetoes = MatchMapVeto::where('match_id', $match->id)
    //             ->with('team:id,team_name')
    //             ->orderBy('sequence_no')
    //             ->get();
    
    //         /*
    //         FINAL MAPS (INCLUDING DECIDER) - UPDATED WITH SIDES AND ACTION
    //         */
    
    //         $matchMaps = DB::table('match_maps')
    //             ->join('maps', 'maps.id', '=', 'match_maps.map_id')
    //             ->where('match_maps.match_id', $match->id)
    //             ->orderBy('map_order')
    //             ->select(
    //                 'match_maps.map_id',
    //                 'maps.name as map_name',
    //                 'match_maps.map_order',
    //                 'match_maps.action',
    //                 'match_maps.team1_side',
    //                 'match_maps.team2_side'
    //             )
    //             ->get();
    
    //         /*
    //         PROCESS MAPS DATA WITH SIDE INFORMATION
    //         */
    
    //         $mapsData = $matchMaps->map(function ($map) use ($match, $team) {
                
    //             // Determine if current user's team is team1 or team2
    //             $isTeam1 = ($match->team1_id == $team->id);
                
    //             // Get current team's side
    //             $currentTeamSide = $isTeam1 ? $map->team1_side : $map->team2_side;
                
    //             // Get opponent team's side
    //             $opponentTeamSide = $isTeam1 ? $map->team2_side : $map->team1_side;
                
    //             // Check if sides are selected
    //             $sidesSelected = ($map->team1_side && $map->team2_side);
                
    //             // Determine if current team can select side
    //             $canSelectSide = false;
                
    //             if ($map->action == 'decider') {
    //                 // Decider map: Any team can select (if sides not selected)
    //                 $canSelectSide = !$sidesSelected;
    //             } else {
    //                 // Pick map: Only opposing team can select
    //                 // For now, we'll just show if sides are selected or not
    //                 $canSelectSide = !$sidesSelected;
    //             }
                
    //             return [
    //                 'map_id' => $map->map_id,
    //                 'map_name' => $map->map_name,
    //                 'map_order' => $map->map_order,
    //                 'action' => $map->action, // 'pick' or 'decider'
    //                 'sides' => [
    //                     'team1_side' => $map->team1_side,
    //                     'team2_side' => $map->team2_side,
    //                     'current_team_side' => $currentTeamSide,
    //                     'opponent_team_side' => $opponentTeamSide,
    //                     'is_selected' => $sidesSelected
    //                 ],
    //                 'can_select_side' => $canSelectSide,
    //                 'status' => $sidesSelected ? 'ready' : 'waiting_for_side_selection'
    //             ];
    //         });
    
    //         /*
    //         SUMMARY STATISTICS
    //         */
    
    //         $totalMaps = $matchMaps->count();
    //         $mapsWithSides = $matchMaps->filter(function($map) {
    //             return $map->team1_side && $map->team2_side;
    //         })->count();
    //         $picksCount = $matchMaps->where('action', 'pick')->count();
    //         $deciderCount = $matchMaps->where('action', 'decider')->count();
    
    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Map veto data fetched successfully',
    //             'data' => [
    //                 'match_info' => [
    //                     'id' => $match->id,
    //                     'best_of' => $match->best_of,
    //                     'team1_id' => $match->team1_id,
    //                     'team2_id' => $match->team2_id,
    //                     'current_team_id' => $team->id,
    //                     'current_team_name' => $team->team_name ?? 'Your Team'
    //                 ],
    //                 'veto_history' => $vetoes,
    //                 'match_maps' => $mapsData,
    //                 'summary' => [
    //                     'total_maps' => $totalMaps,
    //                     'maps_with_sides_selected' => $mapsWithSides,
    //                     'maps_pending_side_selection' => $totalMaps - $mapsWithSides,
    //                     'total_picks' => $picksCount,
    //                     'total_deciders' => $deciderCount,
    //                     'is_veto_complete' => $totalMaps == $match->best_of,
    //                     'is_side_selection_complete' => $mapsWithSides == $totalMaps
    //                 ]
    //             ]
    //         ]);
    
    //     } catch (\Throwable $th) {
    
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Failed to fetch map veto',
    //             'error' => $th->getMessage()
    //         ], 500);
    //     }
    // }
    
    public function chooseStartingSide(Request $request, $match_id, $map_id)
    {
        DB::beginTransaction();
        
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }
            
            // Get team
            $team = TournamentRegistration::where('user_id', $user->id)
                ->where('status', 1)
                ->first();
            
            if (!$team) {
                return response()->json([
                    'status' => false,
                    'message' => 'Team not found'
                ]);
            }
            
            // Get match
            $match = Matchs::find($match_id);
            if (!$match) {
                return response()->json([
                    'status' => false,
                    'message' => 'Match not found'
                ]);
            }
            
            // Check if map exists in match_maps
            $matchMap = DB::table('match_maps')
                ->where('match_id', $match_id)
                ->where('map_id', $map_id)
                ->first();
            
            if (!$matchMap) {
                return response()->json([
                    'status' => false,
                    'message' => 'Map not found in this match'
                ]);
            }
            
            // Check if sides already set
            if ($matchMap->team1_side && $matchMap->team2_side) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sides already selected for this map'
                ]);
            }
            
            // Validate request
            $request->validate([
                'side' => 'required|in:attacker,defender'
            ]);
            
            // Check if it's a decider map or pick map
            $isDecider = ($matchMap->action == 'decider');
            
            if ($isDecider) {
                // For decider map: ANY team can choose side (first come first serve)
                $isTeam1 = ($match->team1_id == $team->id);
                
                if ($isTeam1) {
                    // Team1 is choosing
                    DB::table('match_maps')
                        ->where('match_id', $match_id)
                        ->where('map_id', $map_id)
                        ->update([
                            'team1_side' => $request->side,
                            'team2_side' => ($request->side == 'attacker') ? 'defender' : 'attacker',
                            'updated_at' => now()
                        ]);
                } else {
                    // Team2 is choosing
                    DB::table('match_maps')
                        ->where('match_id', $match_id)
                        ->where('map_id', $map_id)
                        ->update([
                            'team2_side' => $request->side,
                            'team1_side' => ($request->side == 'attacker') ? 'defender' : 'attacker',
                            'updated_at' => now()
                        ]);
                }
                
                DB::commit();
                
                // Get updated sides
                $updatedMap = DB::table('match_maps')
                    ->where('match_id', $match_id)
                    ->where('map_id', $map_id)
                    ->first();
                
                return response()->json([
                    'status' => true,
                    'message' => 'Decider map starting side selected successfully',
                    'data' => [
                        'map_id' => $map_id,
                        'action' => 'decider',
                        'team1_side' => $updatedMap->team1_side,
                        'team2_side' => $updatedMap->team2_side,
                        'selected_by_team' => $team->team_name
                    ]
                ]);
                
            } else {
                // For pick map: Only opposing team can choose side
                // Get the team who picked this map
                $pickedVeto = MatchMapVeto::where('match_id', $match_id)
                    ->where('map_id', $map_id)
                    ->where('action', 'pick')
                    ->first();
                
                if (!$pickedVeto) {
                    return response()->json([
                        'status' => false,
                        'message' => 'This map was not picked'
                    ]);
                }
                
                // Check if current team is the opposing team (not the one who picked)
                if ($pickedVeto->team_id == $team->id) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Your team picked this map. Opposing team chooses starting side.'
                    ]);
                }
                
                // Determine which team is selecting
                $isTeam1 = ($match->team1_id == $team->id);
                
                // Set sides (opposing team gets chosen side)
                if ($isTeam1) {
                    // Team1 is selecting (opposing team), so Team2 (picker) gets chosen side
                    DB::table('match_maps')
                        ->where('match_id', $match_id)
                        ->where('map_id', $map_id)
                        ->update([
                            'team2_side' => $request->side,
                            'team1_side' => ($request->side == 'attacker') ? 'defender' : 'attacker',
                            'updated_at' => now()
                        ]);
                } else {
                    // Team2 is selecting (opposing team), so Team1 (picker) gets chosen side
                    DB::table('match_maps')
                        ->where('match_id', $match_id)
                        ->where('map_id', $map_id)
                        ->update([
                            'team1_side' => $request->side,
                            'team2_side' => ($request->side == 'attacker') ? 'defender' : 'attacker',
                            'updated_at' => now()
                        ]);
                }
                
                DB::commit();
                
                // Get updated sides
                $updatedMap = DB::table('match_maps')
                    ->where('match_id', $match_id)
                    ->where('map_id', $map_id)
                    ->first();
                
                // Get picking team name
                $pickingTeam = TournamentRegistration::find($pickedVeto->team_id);
                
                return response()->json([
                    'status' => true,
                    'message' => 'Starting side selected successfully',
                    'data' => [
                        'map_id' => $map_id,
                        'action' => 'pick',
                        'team1_side' => $updatedMap->team1_side,
                        'team2_side' => $updatedMap->team2_side,
                        'picked_by_team' => $pickingTeam->team_name ?? 'Unknown',
                        'selected_by_team' => $team->team_name
                    ]
                ]);
            }
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Failed to select side',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function index($match_id)
    {
        try {

            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $match = Matchs::find($match_id);

            if (!$match) {
                return response()->json([
                    'status' => false,
                    'message' => 'Match not found'
                ], 404);
            }

            $maps = MatchMap::with('map')
                ->where('match_id', $match_id)
                ->orderBy('map_order')
                ->get();

            $data = $maps->map(function ($item) {

                return [
                    'map_id' => $item->map_id,
                    'map_name' => $item->map->name ?? null,
                    'image' => $item->map && $item->map->image
                        ? asset('storage/' . $item->map->image)
                        : null,
                    'map_order' => $item->map_order,
                    'team1_side' => $item->team1_side,
                    'team2_side' => $item->team2_side,
                    'winner_team_id' => $item->winner_team_id
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Match maps fetched successfully',
                'data' => $data
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => 'Unable to fetch match maps',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function store(Request $request, $match_id)
    {
        try {

            $match = Matchs::find($match_id);

            if (!$match) {
                return response()->json([
                    'status' => false,
                    'message' => 'Match not found'
                ], 404);
            }

            if (!$request->has('maps') || !is_array($request->maps)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Maps payload must be an array'
                ], 422);
            }

            $maps = $request->maps;

            if (count($maps) == 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'At least one map is required'
                ], 422);
            }

            // best_of validation
            if (count($maps) > $match->best_of) {
                return response()->json([
                    'status' => false,
                    'message' => 'You can select only ' . $match->best_of . ' maps'
                ], 422);
            }

            // prevent duplicate entry for same match
            if (MatchMap::where('match_id', $match_id)->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Maps already assigned to this match'
                ], 409);
            }

            $mapIds = [];
            $mapOrders = [];

            foreach ($maps as $index => $map) {

                if (!isset($map['map_id']) || !isset($map['map_order'])) {
                    return response()->json([
                        'status' => false,
                        'message' => "map_id and map_order required at index $index"
                    ], 422);
                }

                // check duplicate map_id
                if (in_array($map['map_id'], $mapIds)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Duplicate map_id found in payload'
                    ], 422);
                }

                $mapIds[] = $map['map_id'];

                // check duplicate map_order
                if (in_array($map['map_order'], $mapOrders)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Duplicate map_order found'
                    ], 422);
                }

                $mapOrders[] = $map['map_order'];

                // check map exists
                $mapExists = Map::where('id', $map['map_id'])->exists();

                if (!$mapExists) {
                    return response()->json([
                        'status' => false,
                        'message' => "Map id {$map['map_id']} does not exist"
                    ], 404);
                }

                // validate sides
                $validSides = ['attacker', 'defender'];

                if (isset($map['team1_side']) && !in_array($map['team1_side'], $validSides)) {
                    return response()->json([
                        'status' => false,
                        'message' => "Invalid team1_side value"
                    ], 422);
                }

                if (isset($map['team2_side']) && !in_array($map['team2_side'], $validSides)) {
                    return response()->json([
                        'status' => false,
                        'message' => "Invalid team2_side value"
                    ], 422);
                }
            }

            DB::beginTransaction();

            foreach ($maps as $map) {

                MatchMap::create([
                    'match_id' => $match_id,
                    'map_id' => $map['map_id'],
                    'map_order' => $map['map_order'],
                    'team1_side' => $map['team1_side'] ?? null,
                    'team2_side' => $map['team2_side'] ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Match maps saved successfully'
            ], 201);
        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Unable to save match maps',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
