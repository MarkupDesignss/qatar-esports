<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TournamentRegistration;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Mail\TeamMemberNotification;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Illuminate\Support\Facades\Redirect;


class TournamentRegistrationController extends Controller
{
    /**
     * Display solo registrations grouped by tournament.
     */
    public function soloIndex(Request $request)
    {
        $tournaments = Tournament::withCount([
            'registrations' => function ($q) {
                $q->where('format', 'solo');
            }
        ])
            ->having('registrations_count', '>', 0)
            ->orderByDesc('registrations_count')
            ->paginate(10);
        return view('admin.tournament-registration.solo', [
            'tournaments' => $tournaments,
        ]);
    }

    /**
     * Display all solo registrations for a specific tournament.
     */
    public function soloDetail($tournamentId)
    {
        $tournament = Tournament::findOrFail($tournamentId);

        $registrations = TournamentRegistration::with(['user'])
            ->where('tournament_id', $tournamentId)
            ->where('type', 'solo')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.tournament-registration.solo-detail', [
            'tournament' => $tournament,
            'registrations' => $registrations,
        ]);
    }

    /**
     * Display team registrations grouped by tournament.
     */
    public function teamIndex(Request $request)
    {
        $tournaments = Tournament::withCount([
            'registrations' => function ($q) {
                $q->where('format', 'team')
                    ->where('is_captain', true);
            }
        ])
            ->having('registrations_count', '>', 0)
            ->orderByDesc('registrations_count')
            ->paginate(10);

        return view('admin.tournament-registration.team', [
            'tournaments' => $tournaments,
        ]);
    }

    /**
     * Display all teams for a specific tournament.
     */
    public function teamDetail($tournamentId)
    {
        $tournament = Tournament::findOrFail($tournamentId);

        $registrations = TournamentRegistration::with(['user'])
            ->where('tournament_id', $tournamentId)
            ->where('type', 'team')
            ->where('is_captain', true)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.tournament-registration.team-detail', [
            'tournament' => $tournament,
            'registrations' => $registrations,
        ]);
    }


    /**
     * Show a single registration. For team captains, show team members.
     */
    public function show($id)
    {
        $registration = TournamentRegistration::with(['tournament', 'user'])->findOrFail($id);

        $members = null;
        if ($registration->type === 'team') {
            // If captain, load team members by invite_link; otherwise load same
            if ($registration->invite_link) {
                $members = TournamentRegistration::with('user')
                    ->where('invite_link', $registration->invite_link)
                    ->orderBy('is_captain', 'desc')
                    ->get();
            } else {
                $members = TournamentRegistration::with('user')
                    ->where('tournament_id', $registration->tournament_id)
                    ->where('team_name', $registration->team_name)
                    ->get();
            }
        }

        return view('admin.tournament-registration.show', [
            'registration' => $registration,
            'members' => $members,
        ]);
    }
    public function toggleStatus($id)
    {
        $team = TournamentRegistration::findOrFail($id);

        // toggle status
        $team->status = $team->status ? 0 : 1;
        $team->save();

        return redirect()->back()->with(
            'success',
            'Team status updated successfully'
        );
    }



    public function exportSoloCsv()
    {
        $tournaments = Tournament::with(['game', 'registrations.user'])
            ->whereHas('registrations', function ($q) {
                $q->where('type', 'solo');
            })
            ->withCount('registrations')
            ->get();

        $filename = 'solo-registrations-' . date('Y-m-d-His') . '.csv';

        $callback = function () use ($tournaments) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'Tournament Title',
                'Game Name',
                'User Name',
                'User Email',
                'Phone',
                'Registered At'
            ]);

            foreach ($tournaments as $tournament) {
                $registrations = $tournament->registrations->where('type', 'solo');
                if ($registrations->isEmpty()) {
                    fputcsv($file, [
                        $tournament->id,
                        $tournament->title,
                        $tournament->game->name ?? '-',
                        '',
                        '',
                        '',
                        '',
                        ''
                    ]);
                    continue;
                }
                foreach ($registrations as $reg) {
                    $user = $reg->user;
                    fputcsv($file, [
                        $tournament->title,
                        $tournament->game->name ?? '-',
                        $user->name ?? $reg->name ?? '',
                        $user->email ?? $reg->email ?? '',
                        $user->phone ?? $reg->phone ?? '',
                        $reg->created_at ?? ''
                    ]);
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function exportTeamCsv()
    {
        $tournaments = Tournament::with(['game', 'registrations.user'])
            ->whereHas('registrations', function ($q) {
                $q->where('type', 'team');
            })
            ->get();

        $filename = 'team-registrations-' . date('Y-m-d-His') . '.csv';

        $callback = function () use ($tournaments) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'Tournament Title',
                'Game',
                'Team Name',
                'Team Tag',
                'Captain Name',
                'Captain Email',
                'Captain Phone',
                'Member Names',
                'Member Emails',
                'Member Phones',
                'Registration Date',
                'Status'
            ]);

            foreach ($tournaments as $tournament) {
                $teamRegs = $tournament->registrations->where('type', 'team');

                // Group by team_name + team_tag (handle nulls)
                $teams = $teamRegs->groupBy(function ($reg) {
                    return ($reg->team_name ?? 'NO_TEAM') . '|' . ($reg->team_tag ?? '');
                });

                foreach ($teams as $members) {
                    $first = $members->first();

                    // Find captain (is_captain = 1), else use first member
                    $captain = $members->firstWhere('is_captain', 1);
                    if (!$captain) {
                        $captain = $first;
                    }

                    $captainUser = $captain->user;

                    $otherMembers = $members->where('is_captain', 0);

                    fputcsv($file, [
                        $tournament->title,
                        $tournament->game->name ?? '-',
                        $first->team_name,
                        $first->team_tag,

                        // Captain details (user first, then registration columns)
                        $captainUser->name ?? $captain->name ?? '',
                        $captainUser->email ?? $captain->email ?? '',
                        $captainUser->phone ?? $captain->phone ?? '',

                        // Other members (fallback to registration name/email/phone)
                        $otherMembers->map(function ($m) {
                            $u = $m->user;
                            return $u->name ?? $m->name ?? '';
                        })->implode(', '),

                        $otherMembers->map(function ($m) {
                            $u = $m->user;
                            return $u->email ?? $m->email ?? '';
                        })->implode(', '),

                        $otherMembers->map(function ($m) {
                            $u = $m->user;
                            return $u->phone ?? $m->phone ?? '';
                        })->implode(', '),

                        $first->created_at,
                        $first->status == 1 ? 'Active' : 'Inactive',
                    ]);
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }


    // Show team registrations list
    public function teamRegistrations($tournamentId)
    {
        $tournament = Tournament::with('game')->findOrFail($tournamentId);

        $registrations = TournamentRegistration::where('tournament_id', $tournamentId)
            ->where('type', 'team')
            ->where('is_captain', true) // Only show team captains for main list
            ->with(['user', 'teamMembers'])
            ->latest()
            ->paginate(15);

        // Count total members for each team
        $registrations->each(function ($team) {
            $team->members_count = TournamentRegistration::where('invite_link', $team->invite_link)
                ->where('type', 'team')
                ->count();
        });

        return view('admin.team-registrations', compact('tournament', 'registrations'));
    }

    // Declare team winner
    public function declareTeamWinner(Request $request, $tournamentId)
    {
        $request->validate([
            'winner_id' => 'required|exists:tournament_registrations,id',
        ]);

        $tournament = Tournament::findOrFail($tournamentId);

        // Check if tournament already has a winner
        if ($tournament->winner_team_id) {
            return back()->with('error', 'Winner already declared for this tournament');
        }

        $winner = TournamentRegistration::find($request->winner_id);

        // Update tournament with winner
        $tournament->update([
            'winner_team_id' => $winner->id,
            'winner_team_name' => $winner->team_name ?? $winner->name,
        ]);

        // Update all team members status
        TournamentRegistration::where('invite_link', $winner->invite_link)
            ->where('type', 'team')
            ->update(['status' => 1]);

        return redirect()->back()->with('success', 'Winner declared successfully!');
    }

    // Reset team winner
    public function resetTeamWinner($tournamentId)
    {
        $tournament = Tournament::findOrFail($tournamentId);

        $tournament->update([
            'winner_team_id' => null,
            'winner_team_name' => null,
        ]);

        return redirect()->back()->with('success', 'Winner reset successfully!');
    }

    // In your TournamentRegistrationController.php

    // View team members with prize distribution
    // public function viewTeamMembers($teamId)
    // {
    //     $captain = TournamentRegistration::with(['user', 'tournament'])
    //         ->findOrFail($teamId);

    //     // Get all team members
    //     $members = TournamentRegistration::where('invite_link', $captain->invite_link)
    //         ->where('type', 'team')
    //         ->with(['user'])
    //         ->get();

    //     $tournament = $captain->tournament;
    //     $winnerTeamId = $tournament->winner_team_id;

    //     return view('admin.tournament-registration.team-members', compact('captain', 'members', 'tournament', 'winnerTeamId'));
    // }
    
    public function viewTeamMembers($teamId)
    {
        $captain = TournamentRegistration::with(['user', 'tournament'])
            ->findOrFail($teamId);
    
        // Get all team members
        $members = TournamentRegistration::where('team_tag', $captain->team_tag)
            ->where('type', 'team')
            ->where('status', 1)
            ->with(['user'])
            ->get();
        $tournament = $captain->tournament;
        $winnerTeamId = $tournament->winner_team_id;
    
    $tournamentEnded = \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($tournament->end_date));
    $tournamentOngoing = \Carbon\Carbon::now()->between(
        \Carbon\Carbon::parse($tournament->start_date),
        \Carbon\Carbon::parse($tournament->end_date)
    );
    // dd($tournamentEnded,$tournamentOngoing);
    return view('admin.tournament-registration.team-members', compact(
        'captain', 
        'members', 
        'tournament', 
        'winnerTeamId',
        'tournamentEnded',
        'tournamentOngoing'
    ));
}

    // Distribute prize (updated)
    // public function distributePrize(Request $request, $tournamentId)
    // {
    //     $request->validate([
    //         'distributions' => 'required|array|min:1',
    //         'distributions.*.member_id' => 'required|exists:tournament_registrations,id',
    //         'distributions.*.amount' => 'required|numeric|min:0',
    //         'distributions.*.rank' => 'nullable|string|max:50',
    //     ]);

    //     $tournament = Tournament::findOrFail($tournamentId);
    //     $totalPrize = $tournament->prize_pool ?? 0;

    //     // Check if tournament has ended
    //     $now = Carbon::now();
    //     $endDate = Carbon::parse($tournament->end_date);
    //     if ($now->lt($endDate)) {
    //         return back()->with('error', 'Tournament has not ended yet. Prize distribution will be available after the tournament ends.');
    //     }

    //     // Get the winning team's captain registration to get invite_link
    //     $winnerCaptain = TournamentRegistration::find($tournament->winner_team_id);
    //     if (!$winnerCaptain) {
    //         return back()->with('error', 'Winning team not found.');
    //     }

    //     DB::beginTransaction();
    //     try {
    //         $totalDistributed = 0;
    //         $distributedCount = 0;

    //         foreach ($request->distributions as $dist) {
    //             if ($dist['amount'] > 0) {
    //                 $member = TournamentRegistration::find($dist['member_id']);

    //                 // Check if member belongs to this tournament
    //                 if ($member->tournament_id != $tournamentId) {
    //                     throw new \Exception('Invalid member selection');
    //                 }

    //                 // Check if member belongs to winning team using invite_link
    //                 // Method 1: Compare invite_link with winner's invite_link
    //                 if ($member->team_tag != $winnerCaptain->team_tag) {
    //                     throw new \Exception('Member does not belong to winning team');
    //                 }

    //                 // OR Method 2: Alternative check using team_name (if you prefer)
    //                 // if ($member->team_+tag != $tournament->winner_team_name) {
    //                 //     throw new \Exception('Member does not belong to winning team');
    //                 // }

    //                 $member->update([
    //                     'prize_amount' => $dist['amount'],
    //                     'prize_rank' => $dist['rank'] ?? null,
    //                     'prize_distributed_at' => now(),
    //                     'is_prize_claimed' => false,
    //                 ]);

    //                 $totalDistributed += $dist['amount'];
    //                 $distributedCount++;
    //             }
    //         }

    //         if ($distributedCount === 0) {
    //             throw new \Exception('Please distribute at least some amount to at least one member');
    //         }

    //         // Check if total distributed exceeds prize pool
    //         if ($totalDistributed > $totalPrize) {
    //             throw new \Exception('Total distributed amount (₹' . number_format($totalDistributed, 2) .
    //                 ') exceeds prize pool (₹' . number_format($totalPrize, 2) . ')');
    //         }

    //         DB::commit();

    //         $message = 'Prize distributed successfully! Total: ₹' . number_format($totalDistributed, 2);
    //         if ($totalDistributed < $totalPrize) {
    //             $message .= ' (Remaining: ₹' . number_format($totalPrize - $totalDistributed, 2) . ' unallocated)';
    //         }

    //         return redirect()->back()->with('success', $message);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Failed to distribute prize: ' . $e->getMessage());
    //     }
    // }
    
    public function distributePrize(Request $request, $tournamentId)
    {
        $request->validate([
            'distributions' => 'required|array|min:1',
            'distributions.*.member_id' => 'required|exists:tournament_registrations,id',
            'distributions.*.amount' => 'required|numeric|min:0',
            'distributions.*.rank' => 'nullable|string|max:50',
        ]);
    
        $tournament = Tournament::findOrFail($tournamentId);
        $totalPrize = $tournament->prize_pool ?? 0;
    
        // Check if tournament has ended
        $now = Carbon::now();
        $endDate = Carbon::parse($tournament->end_date);
        if ($now->lt($endDate)) {
            return back()->with('error', 'Tournament has not ended yet. Prize distribution will be available after the tournament ends.');
        }
    
        // Get the winning team's captain registration
        $winnerCaptain = TournamentRegistration::find($tournament->winner_team_id);
        if (!$winnerCaptain) {
            return back()->with('error', 'Winning team not found.');
        }
    
        // --- PRE-VALIDATION: Collect all errors first ---
        $errors = [];
        $usedRanks = [];
        $totalDistributed = 0;
        $validDistributions = [];
    
        foreach ($request->distributions as $index => $dist) {
            if ($dist['amount'] <= 0) {
                continue; // Skip zero amounts
            }
    
            $member = TournamentRegistration::find($dist['member_id']);
            
            // Check if member exists
            if (!$member) {
                $errors[] = 'Member not found for distribution #' . ($index + 1);
                continue;
            }
    
            // Check if member belongs to this tournament
            if ($member->tournament_id != $tournamentId) {
                $errors[] = 'Invalid member selection for distribution #' . ($index + 1);
                continue;
            }
    
            // Check if member belongs to winning team
            if ($member->team_tag != $winnerCaptain->team_tag) {
                $errors[] = 'Member "' . $member->team_tag . '" does not belong to winning team';
                continue;
            }
    
            // Validate rank uniqueness within the same team
            if (!empty($dist['rank'])) {
                $rankKey = $member->team_tag . '_' . $dist['rank'];
                
                if (in_array($rankKey, $usedRanks)) {
                    $errors[] = 'Duplicate rank "' . $dist['rank'] . '" detected for team "' . $member->team_tag . '"';
                    continue;
                }
                
                $usedRanks[] = $rankKey;
            }
    
            // If all validations pass, store for processing
            $validDistributions[] = [
                'member' => $member,
                'amount' => $dist['amount'],
                'rank' => $dist['rank'] ?? null,
            ];
            
            $totalDistributed += $dist['amount'];
        }
    
        // Check if any errors occurred
        if (!empty($errors)) {
            DB::rollBack();
            return back()->with('error', 'Validation failed: ' . implode(', ', $errors));
        }
    
        // Check if at least one distribution exists
        if (empty($validDistributions)) {
            DB::rollBack();
            return back()->with('error', 'Please distribute at least some amount to at least one member');
        }
    
        // Check if total distributed exceeds prize pool
        if ($totalDistributed > $totalPrize) {
            DB::rollBack();
            return back()->with('error', 'Total distributed amount (QR ' . number_format($totalDistributed, 2) .
                ') exceeds prize pool (QR ' . number_format($totalPrize, 2) . ')');
        }
    
        // --- PROCESS VALID DISTRIBUTIONS ---
        DB::beginTransaction();
        try {
            foreach ($validDistributions as $dist) {
                $dist['member']->update([
                    'prize_amount' => $dist['amount'],
                    'prize_rank' => $dist['rank'],
                    'prize_distributed_at' => now(),
                    'is_prize_claimed' => false,
                ]);
            }
    
            DB::commit();
    
            $message = 'Prize distributed successfully! Total: QR ' . number_format($totalDistributed, 2);
            if ($totalDistributed < $totalPrize) {
                $message .= ' (Remaining: QR ' . number_format($totalPrize - $totalDistributed, 2) . ' unallocated)';
            }
    
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to distribute prize: ' . $e->getMessage());
        }
    }


    // Remove team member
    public function removeTeamMember($registrationId)
    {
        $member = TournamentRegistration::findOrFail($registrationId);

        // Prevent removing captain
        // if ($member->is_captain) {
        //     return back()->with('error', 'Cannot remove team captain');
        // }

        // Check if tournament has started or completed
        $tournament = $member->tournament;
        $now = Carbon::now();
        $startDate = Carbon::parse($tournament->start_date);
        $endDate = Carbon::parse($tournament->end_date);

        // Check if tournament is between start and end date (ongoing)
        if ($now->between($startDate, $endDate)) {
            return back()->with('error', 'Cannot remove members while tournament is ongoing (between start and end date)');
        }
        $member->delete();

        return back()->with('success', 'Team member removed successfully!');
    }

    
    // Show add member form
    public function showAddMemberForm($teamId)
    {
        $captain = TournamentRegistration::with(['tournament', 'user'])->findOrFail($teamId);
        $tournament = $captain->tournament;
    
        // Check if this is the captain's registration
        if ($captain->is_captain != 1) {
            return back()->with('error', 'Only captain can add members to the team');
        }
    
        // Get all members of this team (including inactive)
        $teamMembers = TournamentRegistration::where('tournament_id', $tournament->id)
            ->where('team_name', $captain->team_name)
            ->where('type', 'team')
            ->get();
    
        $registeredUserIds = TournamentRegistration::where('tournament_id', $tournament->id)
            ->pluck('user_id')
            ->toArray();
    
        $availableUsers = User::whereNotIn('id', $registeredUserIds)
            ->where('status', 1) // Only active users
            ->get(['id', 'first_name', 'last_name', 'email', 'mobile']);
    
        // Get tournament status
        $now = now();
        $startDate = \Carbon\Carbon::parse($tournament->start_date);
        $endDate = $tournament->end_date ? \Carbon\Carbon::parse($tournament->end_date) : null;
        
        $tournamentEnded = $endDate ? $now->gt($endDate) : false;
        $tournamentOngoing = $startDate->lte($now) && ($endDate ? $endDate->gte($now) : true);
    
        return view('admin.tournament-registration.add-team-member', compact(
            'captain', 
            'tournament', 
            'teamMembers',
            'availableUsers',
            'tournamentEnded',
            'tournamentOngoing'
        ));
    }
    
    // Add team member manually
     public function addTeamMember(Request $request, $teamId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
    
        $captain = TournamentRegistration::findOrFail($teamId);
        $tournament = $captain->tournament;
    
        // Check if tournament has started
        if ($tournament->start_date <= now()) {
            return back()->with('error', 'Cannot add members after tournament has started');
        }
    
        // Check if user already registered in this tournament
        $existingRegistration = TournamentRegistration::where('tournament_id', $tournament->id)
            ->where('user_id', $request->user_id)
            ->first();
    
        if ($existingRegistration) {
            return back()->with('error', 'User is already registered in this tournament');
        }
    
        // Get the user
        $user = User::findOrFail($request->user_id);
    
        // Check if team is full (only count active members)
        $activeMembersCount = TournamentRegistration::where('tournament_id', $tournament->id)
            ->where('team_name', $captain->team_name)
            ->where('type', 'team')
            ->where('status', 1)
            ->count();
    
        if ($activeMembersCount >= $tournament->team_size) {
            return back()->with('error', 'Team is already at maximum capacity (' . $tournament->team_size . ' members)');
        }
    
        // Create registration with status 1
        $newMember = TournamentRegistration::create([
            'tournament_id' => $tournament->id,
            'user_id' => $user->id,
            'type' => 'team',
            'name' => $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
            'phone' => $user->mobile,
            'team_name' => $captain->team_name,
            'team_tag' => $captain->team_tag,
            'team_logo' => $captain->team_logo,
            'is_captain' => false,
            'invite_link' => $captain->invite_link,
            'status' => 1,
        ]);
    
        // Send email notification to the new member
        try {
            Mail::to($user->email)->send(new TeamMemberNotification(
                $user,
                $newMember,
                $tournament,
                'added'
            ));
        } catch (\Exception $e) {
            \Log::error('Failed to send team member notification: ' . $e->getMessage());
        }
    
        return redirect()->route('admin.team-registrations.members', $teamId)
            ->with('success', 'Team member added successfully! A notification email has been sent.');
    }
    
    /**
     * Change team captain
     */
    public function changeCaptain(Request $request, $teamId)
    {
        $request->validate([
            'new_captain_id' => 'required|exists:tournament_registrations,id',
        ]);
    
        $currentCaptain = TournamentRegistration::findOrFail($teamId);
            // dd($currentCaptain);
        // Verify this is a captain registration
        // if ($currentCaptain->is_captain != 1) {
        //     return back()->with('error', 'The specified team is not a captain registration.');
        // }
    
        $newCaptain = TournamentRegistration::findOrFail($request->new_captain_id);
            // dd($newCaptain,$currentCaptain);
        // Verify they are in the same team
        if ($newCaptain->team_name !== $currentCaptain->team_name || 
            $newCaptain->tournament_id !== $currentCaptain->tournament_id) {
            return back()->with('error', 'The selected user is not in the same team.');
        }
    
        $tournament = $currentCaptain->tournament;
    
        // Check if tournament has started
        if ($tournament->start_date <= now()) {
            return back()->with('error', 'Cannot change captain after tournament has started');
        }
    
        //  Store old captain info for email
        $oldCaptainUser = $currentCaptain->user;
        $newCaptainUser = $newCaptain->user;
    
        //  Demote current captain
        $currentCaptain->is_captain = 0;
        $currentCaptain->save();
    
        //  Promote new captain
        $newCaptain->is_captain = 1;
        $newCaptain->save();
    
        //  Send email to old captain (demoted)
        if ($oldCaptainUser) {
            try {
                Mail::to($oldCaptainUser->email)->send(new TeamMemberNotification(
                    $oldCaptainUser,
                    $currentCaptain,
                    $tournament,
                    'captain_demoted'
                ));
            } catch (\Exception $e) {
                \Log::error('Failed to send demotion email: ' . $e->getMessage());
            }
        }
    
        if ($newCaptainUser) {
            try {
                Mail::to($newCaptainUser->email)->send(new TeamMemberNotification(
                    $newCaptainUser,
                    $newCaptain,
                    $tournament,
                    'captain_promoted'
                ));
            } catch (\Exception $e) {
                \Log::error('Failed to send promotion email: ' . $e->getMessage());
            }
        }
    
        return back()->with('success', 'Team captain has been changed successfully! Both members have been notified.');
    }

    // Show prize distribution page
    public function showPrizeDistribution($tournamentId)
    {
        $tournament = Tournament::findOrFail($tournamentId);

        if (!$tournament->winner_team_id) {
            return redirect()->back()->with('error', 'Please declare a winner first');
        }

        // Get winning team members
        $winner = TournamentRegistration::find($tournament->winner_team_id);
        $members = TournamentRegistration::where('team_tag', $winner->team_tag)
            ->where('type', 'team')
            ->with(['user'])
            ->get();

        $totalPrize = $tournament->prize_pool ?? 0;
        $distributedAmount = $members->sum('prize_amount') ?? 0;
        $remainingAmount = $totalPrize - $distributedAmount;

        return view('admin.tournament-registration.prize-distribution', compact(
            'tournament',
            'members',
            'totalPrize',
            'distributedAmount',
            'remainingAmount'
        ));
    }



    // Mark prize as claimed
    public function markPrizeClaimed($registrationId)
    {
        $member = TournamentRegistration::findOrFail($registrationId);

        if (!$member->prize_amount) {
            return back()->with('error', 'No prize allocated to this member');
        }

        $member->update([
            'is_prize_claimed' => true,
        ]);

        return back()->with('success', 'Prize marked as claimed successfully!');
    }

    // In TournamentRegistrationController.php
    public function resetPrize($tournamentId)
    {
        $tournament = Tournament::findOrFail($tournamentId);

        // Get all members of the winning team
        $winnerCaptain = TournamentRegistration::find($tournament->winner_team_id);
        if (!$winnerCaptain) {
            return back()->with('error', 'Winning team not found.');
        }

        // Reset prize for all team members
        TournamentRegistration::where('invite_link', $winnerCaptain->invite_link)
            ->where('tournament_id', $tournamentId)
            ->update([
                'prize_amount' => null,
                'prize_rank' => null,
                'prize_distributed_at' => null,
                'is_prize_claimed' => false,
            ]);

        return redirect()->back()->with('success', 'Prize distribution reset successfully!');
    }









    // Show solo registrations with prize distribution
    public function soloRegistrations($tournamentId)
    {
        $tournament = Tournament::with('game')->findOrFail($tournamentId);

        $registrations = TournamentRegistration::where('tournament_id', $tournamentId)
            ->where('type', 'solo')
            ->with(['user'])
            ->latest()
            ->paginate(15);

        return view('admin.tournament-registration.solo-registrations', compact('tournament', 'registrations'));
    }

    // Declare solo winner
    public function declareSoloWinner(Request $request, $tournamentId)
    {
        $request->validate([
            'winner_id' => 'required|exists:tournament_registrations,id',
        ]);

        $tournament = Tournament::findOrFail($tournamentId);

        // Check if tournament already has a winner
        if ($tournament->winner_team_id) {
            return back()->with('error', 'Winner already declared for this tournament');
        }

        // Check if tournament has ended
        $now = Carbon::now();
        $endDate = Carbon::parse($tournament->end_date);
        if ($now->lt($endDate)) {
            return back()->with('error', 'Tournament has not ended yet. Winner can be declared after the tournament ends.');
        }

        $winner = TournamentRegistration::find($request->winner_id);

        // Update tournament with winner
        $tournament->update([
            'winner_team_id' => $winner->id,
            'winner_team_name' => $winner->name,
        ]);

        // Update winner status
        $winner->update(['status' => 1]);

        return redirect()->back()->with('success', 'Winner declared successfully!');
    }

    // Reset solo winner
    public function resetSoloWinner($tournamentId)
    {
        $tournament = Tournament::findOrFail($tournamentId);

        $tournament->update([
            'winner_team_id' => null,
            'winner_team_name' => null,
        ]);

        return redirect()->back()->with('success', 'Winner reset successfully!');
    }

    // Distribute solo prize
    public function distributeSoloPrize(Request $request, $tournamentId)
    {
        $request->validate([
            'distributions' => 'required|array|min:1',
            'distributions.*.registration_id' => 'required|exists:tournament_registrations,id',
            'distributions.*.amount' => 'required|numeric|min:0',
            'distributions.*.rank' => 'nullable|string|max:50',
        ]);

        $tournament = Tournament::findOrFail($tournamentId);
        $totalPrize = $tournament->prize_pool ?? 0;

        // Check if tournament has ended
        $now = Carbon::now();
        $endDate = Carbon::parse($tournament->end_date);
        if ($now->lt($endDate)) {
            return back()->with('error', 'Tournament has not ended yet. Prize distribution will be available after the tournament ends.');
        }

        // Check if winner is declared
        if (!$tournament->winner_team_id) {
            return back()->with('error', 'Please declare a winner first.');
        }

        DB::beginTransaction();
        try {
            $totalDistributed = 0;
            $distributedCount = 0;

            foreach ($request->distributions as $dist) {
                if ($dist['amount'] > 0) {
                    $registration = TournamentRegistration::find($dist['registration_id']);

                    // Check if registration belongs to this tournament
                    if ($registration->tournament_id != $tournamentId) {
                        throw new \Exception('Invalid registration selection');
                    }

                    // Check if registration is solo type
                    if ($registration->type != 'solo') {
                        throw new \Exception('Invalid registration type');
                    }

                    $registration->update([
                        'prize_amount' => $dist['amount'],
                        'prize_rank' => $dist['rank'] ?? null,
                        'prize_distributed_at' => now(),
                        'is_prize_claimed' => false,
                    ]);

                    $totalDistributed += $dist['amount'];
                    $distributedCount++;
                }
            }

            if ($distributedCount === 0) {
                throw new \Exception('Please distribute at least some amount to at least one player');
            }

            // Check if total distributed exceeds prize pool
            if ($totalDistributed > $totalPrize) {
                throw new \Exception('Total distributed amount (₹' . number_format($totalDistributed, 2) .
                    ') exceeds prize pool (₹' . number_format($totalPrize, 2) . ')');
            }

            DB::commit();

            $message = 'Prize distributed successfully! Total: ₹' . number_format($totalDistributed, 2);
            if ($totalDistributed < $totalPrize) {
                $message .= ' (Remaining: ₹' . number_format($totalPrize - $totalDistributed, 2) . ' unallocated)';
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to distribute prize: ' . $e->getMessage());
        }
    }

    // Reset solo prize
    public function resetSoloPrize($tournamentId)
    {
        $tournament = Tournament::findOrFail($tournamentId);

        // Reset prize for all solo registrations
        TournamentRegistration::where('tournament_id', $tournamentId)
            ->where('type', 'solo')
            ->update([
                'prize_amount' => null,
                'prize_rank' => null,
                'prize_distributed_at' => null,
                'is_prize_claimed' => false,
            ]);

        return redirect()->back()->with('success', 'Prize distribution reset successfully!');
    }

    // Delete solo registration
    public function deleteSoloRegistration($registrationId)
    {
        $registration = TournamentRegistration::findOrFail($registrationId);

        // Check if tournament has started
        $tournament = $registration->tournament;
        $now = Carbon::now();
        $startDate = Carbon::parse($tournament->start_date);

        if ($now->gte($startDate)) {
            return back()->with('error', 'Cannot delete registration after tournament has started.');
        }

        // Check if this is the winner
        if ($tournament->winner_team_id == $registration->id) {
            return back()->with('error', 'Cannot delete the winner of the tournament.');
        }

        $registration->delete();

        return redirect()->back()->with('success', 'Registration deleted successfully!');
    }

    // Mark prize as claimed for solo
    public function markSoloPrizeClaimed($registrationId)
    {
        $registration = TournamentRegistration::findOrFail($registrationId);

        if (!$registration->prize_amount) {
            return back()->with('error', 'No prize allocated to this player');
        }

        $registration->update([
            'is_prize_claimed' => true,
        ]);

        return back()->with('success', 'Prize marked as claimed successfully!');
    }
}