<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\TournamentRegistration;
use App\Models\Match;
use Carbon\Carbon;
use App\Models\Matchs;
use App\Models\MatchMap;
use Illuminate\Support\Facades\Redirect;

class TournamentMatchController extends Controller
{

    public function index($tournamentId)
    {
        $tournament = Tournament::with('winner')->findOrFail($tournamentId);

        if (!in_array($tournament->format, ['solo', 'team'])) {
            abort(404);
        }

        $matches = Matchs::with([
                'team1',
                'team2',
                'winner',
                'maps.map'
            ])
            ->where('tournament_id', $tournament->id)
            ->orderByRaw("
                FIELD(round,'Round 1','Quarterfinal','Semifinal','Final')
            ")
            ->orderBy('match_order')
            ->get();

        return view('admin.match.index', compact('tournament', 'matches'));
    }

    /**
     * Show create match form
     */
    public function create(Request $request, $tournamentId)
    {
        $tournament = Tournament::findOrFail($tournamentId);
        if ($tournament->winner_team_id) {
        return redirect()
            ->route('admin.match.index', $tournamentId)
            ->withErrors(['error' => 'Tournament is completed. You cannot create new matches.']);
    }
        $round = $request->round;
        $teams = collect();

        if ($round) {
            $teams = $this->getEligibleTeams($tournament, $round);
        }

        return view('admin.match.create', compact(
            'tournament',
            'teams',
            'round'
        ));
    }

    /**
     * Store match (ADMIN ONLY â€“ no auto logic)
     */


    public function store(Request $request, $tournamentId)
    {
        $tournament = Tournament::findOrFail($tournamentId);
        $request->validate([
            'round'       => 'required|string',
            'best_of'       => 'nullable|string',
            'match_order' => 'required|integer|min:1',
            'team1_id'    => 'nullable|exists:tournament_registrations,id',
            'team2_id'    => 'nullable|exists:tournament_registrations,id',
            'match_date' => [
                    'required',
                    'date',
                    'after_or_equal:' . \Carbon\Carbon::parse($tournament->registration_start)->toDateString(),
                    'before_or_equal:' . \Carbon\Carbon::parse($tournament->registration_end)->toDateString(),
                ],
            'match_time'  => 'nullable',
            'banner'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if (
            $request->team1_id &&
            $request->team2_id &&
            $request->team1_id === $request->team2_id
        ) {
            return back()
                ->withErrors(['team2_id' => 'Both teams cannot be same'])
                ->withInput();
        }

        /* ------------------------------------
     | ️⃣ Fetch team names from registrations
     ------------------------------------ */
        $team1 = $request->team1_id
            ? TournamentRegistration::find($request->team1_id)
            : null;

        $team2 = $request->team2_id
            ? TournamentRegistration::find($request->team2_id)
            : null;

        /* ------------------------------------
     | ️⃣ Banner upload
     ------------------------------------ */
        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')
                ->store('matches/banners', 'public');
        }

        /* ------------------------------------
     | ️⃣ Create Match (ID + NAME both)
     ------------------------------------ */
        Matchs::create([
            'tournament_id' => $tournamentId,
            'round'         => $request->round,
            'best_of'         => $request->best_of,
            'match_order'   => $request->match_order,
            

            // IDs
            'team1_id' => $team1?->id,
            'team2_id' => $team2?->id,

            //  NAMES (IMPORTANT)
            'team1_name' => $team1?->team_name,
            'team2_name' => $team2?->team_name,

            // winner (initially null)
            'winner_id'        => null,
            'winner_team_name' => null,

            'status'      => 'pending',
            'match_date'  => $request->match_date,
            'match_time'  => $request->match_time,
            'banner'      => $bannerPath,
        ]);

        return redirect()
            ->route('admin.match.index', $tournamentId)
            ->with('success', 'Match created successfully');
    }
    
    public function edit($tournamentId, $matchId)
    {
        $tournament = Tournament::findOrFail($tournamentId);
        $match = Matchs::findOrFail($matchId);
    
        $teams = $this->getEligibleTeams(
            $tournament,
            $match->round,
            $match->id 
            );
    
        return view('admin.match.edit', compact('tournament', 'match', 'teams'));
    }
    
    public function update(Request $request, $tournamentId, $matchId)
    {
        $match = Matchs::findOrFail($matchId);
    
        $request->validate([
            'round'       => 'required|string',
            'best_of'     => 'required|string',
            'match_order' => 'required|integer|min:1',
            'team1_id'    => 'nullable|exists:tournament_registrations,id',
            'team2_id'    => 'nullable|exists:tournament_registrations,id',
             'match_date'  => [
                    'required',
                    'date',
                    'after_or_equal:' . $tournament->start_date,
                    'before_or_equal:' . $tournament->end_date,
                ],
            'match_time'  => 'nullable',
            'banner'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
    
        // Prevent same team
        if (
            $request->team1_id &&
            $request->team2_id &&
            $request->team1_id === $request->team2_id
        ) {
            return back()
                ->withErrors(['team2_id' => 'Both teams cannot be same'])
                ->withInput();
        }
    
        /* -------------------------------
        | Fetch Teams
        --------------------------------*/
        $team1 = $request->team1_id
            ? TournamentRegistration::find($request->team1_id)
            : null;
    
        $team2 = $request->team2_id
            ? TournamentRegistration::find($request->team2_id)
            : null;
    
        /* -------------------------------
        | Banner Upload (Replace old)
        --------------------------------*/
        if ($request->hasFile('banner')) {
            // delete old banner
            if ($match->banner && \Storage::disk('public')->exists($match->banner)) {
                \Storage::disk('public')->delete($match->banner);
            }
    
            $bannerPath = $request->file('banner')
                ->store('matches/banners', 'public');
    
            $match->banner = $bannerPath;
        }
    
        /* -------------------------------
        | Update Match
        --------------------------------*/
        $match->update([
            'round'       => $request->round,
            'best_of'     => $request->best_of,
            'match_order' => $request->match_order,
    
            // IDs
            'team1_id' => $team1?->id,
            'team2_id' => $team2?->id,
    
            // Names
            'team1_name' => $team1?->team_name,
            'team2_name' => $team2?->team_name,
    
            'match_date' => $request->match_date,
            'match_time' => $request->match_time,
        ]);
    
        return redirect()
            ->route('admin.match.index', $tournamentId)
            ->with('success', 'Match updated successfully');
    }
    
    public function destroy($tournamentId, $matchId)
    {
        $match = Matchs::findOrFail($matchId);
    
        // Delete banner if exists
        if ($match->banner && \Storage::disk('public')->exists($match->banner)) {
            \Storage::disk('public')->delete($match->banner);
        }
    
        $match->delete();
    
        return redirect()
            ->route('admin.match.index', $tournamentId)
            ->with('success', 'Match deleted successfully');
    }
    /**
     * Update winner (MANUAL)
     */
     
     public function updateWinner(Request $request, $matchId)
    {
        $request->validate([
            'winner_id' => 'required|exists:tournament_registrations,id',
        ]);
    
        $match = Matchs::with(['team1', 'team2'])->findOrFail($matchId);
    
        if ($match->status === 'completed') {
            return back()->withErrors([
                'winner_id' => 'Winner already declared'
            ]);
        }
    
        if (!in_array($request->winner_id, [
            $match->team1_id,
            $match->team2_id
        ])) {
            abort(403, 'Invalid winner selection');
        }
    
        //  Fetch winner registration to get team name
        $winnerTeam = TournamentRegistration::find($request->winner_id);
        $winnerName = $winnerTeam?->team_name ?? $winnerTeam?->name ?? 'Unknown';
    
        $match->update([
            'winner_id'        => $request->winner_id,
            'winner_team_name' => $winnerName,
            'status'           => 'completed',
            'played_at'        => now(),
        ]);
    
        //  If Final → update tournament winner
        if ($match->round === 'Final') {
            Tournament::where('id', $match->tournament_id)
                ->update([
                    'winner_team_id'   => $request->winner_id,
                    'winner_team_name' => $winnerName,
                ]);
        }
    
        return redirect()->back()->with(
            'success',
            'Winner updated successfully'
        );
    }
    
    private function getEligibleTeams(Tournament $tournament, $round,$excludeMatchId= null)
    {
        $tournamentId = $tournament->id;
    
        // Teams already used in this round
        $usedTeamIds = $this->getUsedTeamIds($tournamentId, $round,$excludeMatchId);
    
        /**
         * ðŸ”¹ BASE QUERY (SOLO vs TEAM)
         */
        $baseQuery = TournamentRegistration::where('tournament_id', $tournamentId)
            ->where('status', '1')
            ->when($tournament->format === 'team', function ($q) {
                $q->where('is_captain', true);
            });
    
        $totalParticipants = (clone $baseQuery)->count();
    
        /**
         * âœ… SPECIAL CASE: ONLY 2 PARTICIPANTS â†’ DIRECT FINAL
         */
        if ($totalParticipants === 2 && $round === 'Final') {
            return $baseQuery
                ->whereNotIn('id', $usedTeamIds)
                ->get();
        }
    
        /**
         * ðŸ”¹ ROUND 1 â†’ ALL PARTICIPANTS
         */
        if ($round === 'Round 1') {
            return $baseQuery
                ->whereNotIn('id', $usedTeamIds)
                ->get();
        }
    
        /**
         * ðŸ”¹ QUARTERFINAL â†’ ROUND 1 WINNERS
         */
        if ($round === 'Quarterfinal') {
            return Matchs::where('tournament_id', $tournamentId)
                ->where('round', 'Round 1')
                ->where('status', 'completed')
                ->with('winner')
                ->get()
                ->pluck('winner')
                ->filter(fn ($team) => $team && !in_array($team->id, $usedTeamIds))
                ->unique('id')
                ->values();
        }
    
        /**
         * ðŸ”¹ SEMIFINAL â†’ QUARTERFINAL WINNERS
         */
        if ($round === 'Semifinal') {
            return Matchs::where('tournament_id', $tournamentId)
                ->where('round', 'Quarterfinal')
                ->where('status', 'completed')
                ->with('winner')
                ->get()
                ->pluck('winner')
                ->filter(fn ($team) => $team && !in_array($team->id, $usedTeamIds))
                ->unique('id')
                ->values();
        }
    
        /**
         * ðŸ”¹ FINAL â†’ SEMIFINAL WINNERS OR ROUND 1 WINNERS
         */
        if ($round === 'Final') {
    
            // Prefer semifinal winners
            $semiWinners = Matchs::where('tournament_id', $tournamentId)
                ->where('round', 'Semifinal')
                ->where('status', 'completed')
                ->with('winner')
                ->get()
                ->pluck('winner')
                ->filter(fn ($team) => $team && !in_array($team->id, $usedTeamIds))
                ->unique('id')
                ->values();
    
            if ($semiWinners->count() > 0) {
                return $semiWinners;
            }
    
            // Fallback â†’ Round 1 winners
            return Matchs::where('tournament_id', $tournamentId)
                ->where('round', 'Round 1')
                ->where('status', 'completed')
                ->with('winner')
                ->get()
                ->pluck('winner')
                ->filter(fn ($team) => $team && !in_array($team->id, $usedTeamIds))
                ->unique('id')
                ->values();
        }
    
        return collect();
    }

    /**
     * Teams already used in SAME ROUND
    //  */
    private function getUsedTeamIds($tournamentId, $round, $excludeMatchId = null)
    {
        return Matchs::where('tournament_id', $tournamentId)
            ->where('round', $round)
            ->when($excludeMatchId, function ($q) use ($excludeMatchId) {
                $q->where('id', '!=', $excludeMatchId);
            })
            ->get()
            ->flatMap(function ($match) {
                return [
                    $match->team1_id,
                    $match->team2_id
                ];
            })
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }
    // private function getUsedTeamIds($tournamentId, $round)
    // {
    //     return Matchs::where('tournament_id', $tournamentId)
    //         ->where('round', $round)
    //         ->get()
    //         ->flatMap(function ($match) {
    //             return [
    //                 $match->team1_id,
    //                 $match->team2_id
    //             ];
    //         })
    //         ->filter()
    //         ->unique()
    //         ->values()
    //         ->toArray();
    // }
    public function updateDetails(Request $request, $matchId)
    {
        $request->validate([
            'match_video' => 'nullable|url',
            'match_date' => 'nullable',
            'match_time' => 'nullable',
        ]);

        Matchs::where('id', $matchId)->update([
            'match_video' => $request->match_video,
            'match_date' => $request->match_date,
            'match_time' => $request->match_time,
        ]);

        return back()->with('success', 'Match details updated');
    }

//     public function updateMapResult(Request $request,$mapId)
// {
//     $request->validate([
//         'winner_team_id'=>'required'
//     ]);

//     $map = MatchMap::findOrFail($mapId);

//     $map->update([
//         'winner_team_id'=>$request->winner_team_id
//     ]);

//     $match = $map->match;

//     $winsNeeded = ceil($match->best_of / 2);

//     $team1Wins = MatchMap::where('match_id',$match->id)
//         ->where('winner_team_id',$match->team1_id)
//         ->count();

//     $team2Wins = MatchMap::where('match_id',$match->id)
//         ->where('winner_team_id',$match->team2_id)
//         ->count();

//     if($team1Wins >= $winsNeeded){
//         $match->update([
//             'winner_id'=>$match->team1_id,
//             'status'=>'completed'
//         ]);
//     }

//     if($team2Wins >= $winsNeeded){
//         $match->update([
//             'winner_id'=>$match->team2_id,
//             'status'=>'completed'
//         ]);
//     }

//     return back()->with('success','Map result saved');
// }

    public function updateMapResult(Request $request, $mapId)
{
    $request->validate([
        'winner_team_id' => 'required|exists:tournament_registrations,id'
    ]);

    $map = MatchMap::with('match')->findOrFail($mapId);
    $match = $map->match;

    // ensure selected team belongs to match
    if (!in_array($request->winner_team_id, [
        $match->team1_id,
        $match->team2_id
    ])) {
        abort(403,'Invalid team selected');
    }

    // save map winner
    $map->update([
        'winner_team_id' => $request->winner_team_id
    ]);

    // count map wins
    $team1Wins = MatchMap::where('match_id',$match->id)
        ->where('winner_team_id',$match->team1_id)
        ->count();

    $team2Wins = MatchMap::where('match_id',$match->id)
        ->where('winner_team_id',$match->team2_id)
        ->count();

    $totalMaps = MatchMap::where('match_id',$match->id)->count();

    // if all maps finished
    if(($team1Wins + $team2Wins) == $totalMaps){

        $winnerId = null;

        if($team1Wins > $team2Wins){
            $winnerId = $match->team1_id;
        }

        if($team2Wins > $team1Wins){
            $winnerId = $match->team2_id;
        }

        if($winnerId){

            $winnerTeam = TournamentRegistration::find($winnerId);

            $match->update([
                'winner_id' => $winnerId,
                'winner_team_name' => $winnerName,
                'status' => 'completed',
                'played_at' => now()
            ]);
        }
    }

    return back()->with('success','Map winner saved');
}
    

}