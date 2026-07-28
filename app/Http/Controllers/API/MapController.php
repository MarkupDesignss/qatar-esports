<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Map;
use App\Models\Tournament;
use App\Models\Matchs;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MapController extends Controller
{


    // public function mapsByGame($game_id)
    // {
    //     try {
    
    //         $tournament = Tournament::where('game_id', $game_id)->first();
    
    //         if (!$tournament) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'No tournament found for this game',
    //                 'data' => []
    //             ]);
    //         }
    
    //         // FIXED HERE
    //         $match = Matchs::where('tournament_id', $tournament->id)
    //             ->where('status', 'pending')
    //             ->orderBy('match_date')
    //             ->orderBy('match_time')
    //             ->first();
    
    //         if (!$match) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'No upcoming match found',
    //                 'data' => []
    //             ]);
    //         }
    
    //         $matchStart = Carbon::parse($match->match_date . ' ' . $match->match_time);
    
    
    //         if (Carbon::now()->lt($matchStart->copy()->subMinutes(5))) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Maps will be available 5 minutes before match starts',
    //                 'data' => []
    //             ]);
    //         }
    
    //         $maps = Map::where('game_id', $game_id)
    //             ->where('is_active', 1)
    //             ->select('id', 'name', 'slug', 'image')
    //             ->get();
    
    //         if ($maps->isEmpty()) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'No maps found for this game',
    //                 'data' => []
    //             ]);
    //         }
    
    //         $maps->transform(function ($map) {
    //             $map->image = $map->image
    //                 ? asset('storage/' . $map->image)
    //                 : null;
    //             return $map;
    //         });
    
    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Maps fetched successfully',
    //             'data' => $maps
    //         ]);
    
    //     } catch (\Throwable $th) {
    
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Unable to fetch maps',
    //             'error' => $th->getMessage()
    //         ], 500);
    //     }
    // }
    
    public function mapsByGame($game_id)
    {
        try {
            $tournament = Tournament::where('game_id', $game_id)->first();
    
            if (!$tournament) {
                return response()->json([
                    'status' => false,
                    'message' => 'No tournament found for this game',
                    'data' => []
                ]);
            }
    
            $match = Matchs::where('tournament_id', $tournament->id)
                ->where('status', 'pending')
                ->orderBy('match_date')
                ->orderBy('match_time')
                ->first();
    
            if (!$match) {
                return response()->json([
                    'status' => false,
                    'message' => 'No upcoming match found',
                    'data' => []
                ]);
            }
    
            $matchStart = Carbon::parse($match->match_date . ' ' . $match->match_time);
    
            if (Carbon::now()->lt($matchStart->copy()->subMinutes(5))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Maps will be available 5 minutes before match starts',
                    'data' => [],
                    'match_start_date' => $match->match_date,
                    'match_start_time' => $match->match_time
                ]);
            }
    
            $maps = Map::where('game_id', $game_id)
                ->where('is_active', 1)
                ->select('id', 'name', 'slug', 'image')
                ->get();
    
            if ($maps->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No maps found for this game',
                    'data' => []
                ]);
            }
    
            $maps->transform(function ($map) {
                $map->image = $map->image ? asset('storage/' . $map->image) : null;
                return $map;
            });
    
            return response()->json([
                'status' => true,
                'message' => 'Maps fetched successfully',
                'data' => $maps,
                'match_start_date' => $match->match_date,
                'match_start_time' => $match->match_time
            ]);
    
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Unable to fetch maps',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
