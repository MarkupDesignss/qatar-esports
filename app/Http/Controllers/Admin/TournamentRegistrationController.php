<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TournamentRegistration;
use App\Models\Tournament;

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
				$q->where('type', 'solo');
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
				$q->where('type', 'team')
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

	
}
