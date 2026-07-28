<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TournamentRegistration;
    use App\Models\Tournament;
use Illuminate\Support\Collection;
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
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
    
            fputcsv($file, [
                'Tournament Title', 'Game Name',
                'User Name', 'User Email', 'Phone', 'Registered At'
            ]);
    
            foreach ($tournaments as $tournament) {
                $registrations = $tournament->registrations->where('type', 'solo');
                if ($registrations->isEmpty()) {
                    fputcsv($file, [
                        $tournament->id, $tournament->title, $tournament->game->name ?? '-',
                        '', '', '', '', ''
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
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
    
            fputcsv($file, [
                'Tournament Title', 'Game',
                'Team Name', 'Team Tag',
                'Captain Name', 'Captain Email', 'Captain Phone',
                'Member Names', 'Member Emails', 'Member Phones',
                'Registration Date', 'Status'
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
	
}
