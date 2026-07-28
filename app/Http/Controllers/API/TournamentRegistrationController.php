<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\TournamentRegistration;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\TournamentInviteMail;
use Illuminate\Support\Facades\Validator;

class TournamentRegistrationController extends Controller
{
    /**
     * Solo Registration
     * POST /api/tournaments/{id}/register/solo
     */
    // public function soloRegister(Request $request, $id)
    // {
    //     $user = $request->user();
    //     $tournament = Tournament::findOrFail($id);

    //     // if (!$tournament->is_registration_open) {
    //     //     return response()->json(['message' => 'Registration is closed'], 422);
    //     // }
        
    //     if (Carbon::parse($tournament->registration_end)->lt(Carbon::now())) {
    //         return response()->json([
    //             'message' => 'Registration is closed'
    //         ], 422);
    //     }

    //     if ($tournament->max_participants && $tournament->registered_participants >= $tournament->max_participants) {
    //         return response()->json(['message' => 'Tournament is full'], 422);
    //     }

    //     $exists = TournamentRegistration::where('tournament_id', $tournament->id)
    //         ->where('user_id', $user->id)
    //         ->where('type', 'solo')
    //         ->where('status', 1)
    //         ->first();

    //     if ($exists) {
    //         return response()->json(['message' => 'Already registered'], 200);
    //     }

    //     $registration = TournamentRegistration::create([
    //         'tournament_id' => $tournament->id,
    //         'type' => 'solo',
    //         'name' => $user->first_name . ' ' . $user->last_name,
    //         'email' => $user->email,
    //         'phone' => $user->mobile ?? $user->phone,
    //         'user_id' => $user->id,
    //     ]);

    //     $tournament->increment('registered_participants');

    //     return response()->json([
    //         'message' => 'Successfully registered for solo',
    //         'registration' => $registration
    //     ]);
    // }
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
    
        // Check if user already has a registration (any status)
        $existingRegistration = TournamentRegistration::where('tournament_id', $tournament->id)
            ->where('user_id', $user->id)
            ->where('type', 'solo')
            ->first();
        if ($existingRegistration) {
            // If status is 1, user is already actively registered
            if ($existingRegistration->status == 1) {
                return response()->json(['message' => 'Already registered'], 200);
            }
            
            // If status is 0, update it to 1
            $existingRegistration->update(['status' => 1]);
            
            // Increment participant count since user is re-activating registration
            $tournament->increment('registered_participants');
    
            return response()->json([
                'message' => 'Registration reactivated successfully',
                'registration' => $existingRegistration
            ]);
        }
    
        // Create new registration if no existing record found
        $registration = TournamentRegistration::create([
            'tournament_id' => $tournament->id,
            'type' => 'solo',
            'name' => $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
            'phone' => $user->mobile ?? $user->phone,
            'user_id' => $user->id,
            'status' => 1 // Explicitly set status to 1
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
    // public function teamRegister(Request $request, $id)
    // {
    //     $user = $request->user();

    //     $request->validate([
    //         'team_name' => 'required|string|max:191',
    //         'team_tag' => 'required|string|max:50',
    //         'team_logo' => 'nullable|image|max:2048',
    //     ]);
    
    //     $tournament = Tournament::findOrFail($id);
    //     // if (!$tournament->is_registration_open) {
    //     //     return response()->json(['message' => 'Registration is closed'], 422);
    //     // }
        
    //     $exists = TournamentRegistration::where('tournament_id', $tournament->id)
    //         ->where('user_id', $user->id)
    //         ->where('type', 'team')
    //         ->where('status', 1)
    //         ->exists();
        
    //     if ($exists) {
    //         return response()->json([
    //             'message' => 'You have already registered in this tournament.'
    //         ], 422);
    //     }
        
    //     if (Carbon::parse($tournament->registration_end)->lt(Carbon::now())) {
    //         return response()->json([
    //             'message' => 'Registration is closed'
    //         ], 422);
    //     }

    //     // Duplicate team check
    //     $duplicate = TournamentRegistration::where('tournament_id', $tournament->id)
    //         ->where('type', 'team')
    //         ->where(function ($q) use ($request) {
    //             $q->where('team_name', $request->team_name)
    //               ->orWhere('team_tag', $request->team_tag);
    //         })
    //         ->exists();

    //     if ($duplicate) {
    //         return response()->json(['message' => 'Team name or tag already exists'], 200);
    //     }
        
        
    //         if (
    //             !is_null($tournament->max_participants) &&
    //             $tournament->registered_participants > $tournament->max_participants
    //         ) {
    //             throw ValidationException::withMessages([
    //                 'message' => 'Tournament registration is full.'
    //             ]);
    //         }
    //     // Upload team logo
    //     $teamLogoPath = null;
    //     if ($request->hasFile('team_logo')) {
    //         $teamLogoPath = $request->file('team_logo')->store('teams', 'public');
    //     }

    //     // Generate unique invite code
    //     do {
    //         $inviteCode = Str::random(16);
    //     } while (TournamentRegistration::where('invite_link', $inviteCode)->exists());

    //     $registration = TournamentRegistration::create([
    //         'tournament_id' => $tournament->id,
    //         'type' => 'team',
    //         'team_name' => $request->team_name,
    //         'team_tag' => $request->team_tag,
    //         'team_logo' => $teamLogoPath,
    //         'is_captain' => true,
    //         'invite_link' => $inviteCode,
    //         'name' => $user->first_name . ' ' . $user->last_name,
    //         'email' => $user->email,
    //         'phone' => $user->mobile ?? $user->phone,
    //         'user_id' => $user->id,
    //     ]);

    //     $tournament->increment('registered_participants');

    //     // Frontend invite URL
    //     //$tournamentSlug = Str::slug($tournament->title); 
    //     $tournamentTitle = rawurlencode($tournament->title);
    //     $inviteUrl = 'https://www.markupdesigns.net/qec-web/tourmainpage/'.$tournamentTitle.'?invite=' . $inviteCode;


    //     // SEND INVITE EMAIL (OPTIONAL BUT RECOMMENDED)
    //     Mail::to($user->email)->send(
    //         new TournamentInviteMail(
    //             $inviteUrl,
    //             $tournament->title,
    //             $request->team_name
    //         )
    //     );

    //     return response()->json([
    //         'message' => 'Team created successfully. An invite link has been sent to your email.',
    //         'registration' => $registration,
    //         'invite_link' => $inviteUrl,
    //         'team_logo_url' => $teamLogoPath ? asset('storage/' . $teamLogoPath) : null,
    //     ]);
    // }
    public function teamRegister(Request $request, $id)
    {
        $user = $request->user();

        $request->validate([
            'team_name' => 'required|string|unique:tournament_registrations|max:191',
            'team_tag' => 'required|string|unique:tournament_registrations|max:50',
            'team_logo' => 'nullable|image|max:2048',
        ]);
    
        $tournament = Tournament::findOrFail($id);
        // if (!$tournament->is_registration_open) {
        //     return response()->json(['message' => 'Registration is closed'], 422);
        // }
        
        // Check if user has any registration (any status)
        $existingRegistration = TournamentRegistration::where('tournament_id', $tournament->id)
            ->where('user_id', $user->id)
            ->where('type', 'team')
            ->first();
        if ($existingRegistration) {
            // If status is 1, user is already actively registered
            if ($existingRegistration->status == 1) {
                return response()->json([
                    'message' => 'You have already registered in this tournament.'
                ], 422);
            }
            
            // If status is 0, update it to 1 and reactivate
            // Check if tournament is full before reactivating
            if ($tournament->max_participants && $tournament->registered_participants >= $tournament->max_participants) {
                return response()->json(['message' => 'Tournament is full'], 422);
            }
            
            // Update the existing registration
            $existingRegistration->update([
                'status' => 1,
                'team_name' => $request->team_name,
                'team_tag' => $request->team_tag,
                // Only update logo if a new one is uploaded
                'team_logo' => $request->hasFile('team_logo') 
                    ? $request->file('team_logo')->store('teams', 'public') 
                    : $existingRegistration->team_logo,
                'name' => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'phone' => $user->mobile ?? $user->phone,
            ]);
            
            // Increment participant count
            $tournament->increment('registered_participants');
            
            // Generate invite URL for reactivation
            $tournamentTitle = rawurlencode($tournament->title);
            $inviteUrl = 'https://www.markupdesigns.net/qec-web/tourmainpage/'.$tournamentTitle.'?invite=' . $existingRegistration->invite_link;
            
            // Send email notification
            Mail::to($user->email)->send(
                new TournamentInviteMail(
                    $inviteUrl,
                    $tournament->title,
                    $request->team_name
                )
            );
    
            return response()->json([
                'message' => 'Team registration reactivated successfully. An invite link has been sent to your email.',
                'registration' => $existingRegistration,
                'invite_link' => $inviteUrl,
                'team_logo_url' => $existingRegistration->team_logo ? asset('storage/' . $existingRegistration->team_logo) : null,
            ]);
        }
    
        // Check registration deadline
        if (Carbon::parse($tournament->registration_end)->lt(Carbon::now())) {
            return response()->json([
                'message' => 'Registration is closed'
            ], 422);
        }
    
        // Check tournament capacity
        if ($tournament->max_participants && $tournament->registered_participants >= $tournament->max_participants) {
            return response()->json(['message' => 'Tournament is full'], 422);
        }
    
        // Duplicate team check (only for new registrations)
        $duplicate = TournamentRegistration::where('tournament_id', $tournament->id)
            ->where('type', 'team')
            ->where(function ($q) use ($request) {
                $q->where('team_name', $request->team_name)
                  ->orWhere('team_tag', $request->team_tag);
            })
            ->where('status', 1) // Only check active registrations
            ->exists();
    
        if ($duplicate) {
            return response()->json(['message' => 'Team name or tag already exists'], 422);
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
            'status' => 1, // Explicitly set status
        ]);
    
        $tournament->increment('registered_participants');
    
        // Frontend invite URL
        $tournamentTitle = rawurlencode($tournament->title);
        $inviteUrl = 'https://www.markupdesigns.net/qec-web/tourmainpage/'.$tournamentTitle.'?invite=' . $inviteCode;
    
        // Send invite email
        Mail::to($user->email)->send(
            new TournamentInviteMail(
                $inviteUrl,
                $tournament->title,
                $request->team_name
            )
        );
    
        return response()->json([
            'message' => 'Team created successfully. An invite link has been sent to your email.',
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
        // $tournamentTitle = rawurlencode($tournament->title);

        // // $inviteUrl = 'https://www.markupdesigns.net/qec-web/tourmainpage/'
        // $inviteUrl = 'http://localhost:5173/qec-web/tourmainpage/'
        //     . $tournamentTitle
        //     . '?invite=' . $registration->invite_link;
        $tournamentTitle = rawurlencode($tournament->title);

        $inviteUrl = 'https://www.markupdesigns.net/qec-web/tourmainpage/' .
            $tournamentTitle .
            '?invite=' . $registration->invite_link;

        return response()->json([
            'invite_link' => $inviteUrl
        ]);
    }

    /**
     * Join Team via Invite Code
     * POST /api/tournaments/join-team
     */
    // public function joinTeam(Request $request)
    // {
    //     $user = $request->user();

    //     // Validate request
    //     $request->validate([
    //         'invite_link' => 'required|string',
    //     ]);

    //     $inviteLink = $request->invite_link;

    //     // Extract invite code if frontend sent full URL
    //     if (strpos($inviteLink, 'invite=') !== false) {
    //         parse_str(parse_url($inviteLink, PHP_URL_QUERY), $queryParams);
    //         $inviteCode = $queryParams['invite'] ?? $inviteLink;
    //     } else {
    //         $inviteCode = $inviteLink;
    //     }

    //     // Find the team using the invite code stored in DB
    //     $team = TournamentRegistration::where('invite_link', $inviteCode)
    //         ->where('type', 'team')
    //         ->firstOrFail();

    //     // Get the tournament
    //     $tournament = Tournament::findOrFail($team->tournament_id);
        
    //     $exists = TournamentRegistration::where('tournament_id', $tournament->id)
    //         ->where('user_id', $user->id)
    //         ->where('type', 'team')
    //         ->exists();
        
    //     if ($exists) {
    //         return response()->json([
    //             'message' => 'You have already registered in this tournament.'
    //         ], 422);
    //     }

    //     // Check if registration is open
    //     // if (!$tournament->is_registration_open) {
    //     //     return response()->json(['message' => 'Registration is closed'], 422);
    //     // }
        
    //     if (Carbon::parse($tournament->registration_end)->lt(Carbon::now())) {
    //         return response()->json([
    //             'message' => 'Registration is closed'
    //         ], 200);
    //     }

    //     // Check if user already joined this team
    //     $exists = TournamentRegistration::where('invite_link', $team->invite_link)
    //         ->where('user_id', $user->id)
    //         ->exists();

    //     if ($exists) {
    //         return response()->json(['message' => 'Already part of this team'], 200);
    //     }

    //     // Team size check
    //     $membersCount = TournamentRegistration::where('invite_link', $team->invite_link)->count();
    //     if ($tournament->team_size && $membersCount >= $tournament->team_size) {
    //         return response()->json(['message' => 'Team is full'], 422);
    //     }

    //     // Register the user to the team
    //     $registration = TournamentRegistration::create([
    //         'tournament_id' => $tournament->id,
    //         'type' => 'team',
    //         'team_name' => $team->team_name,
    //         'team_tag' => $team->team_tag,
    //         'team_logo' => $team->team_logo,
    //         'is_captain' => false,
    //         'invite_link' => $team->invite_link, // store invite code
    //         'user_id' => $user->id,
    //         'name' => $user->name,
    //         'email' => $user->email,
    //         'phone' => $user->phone ?? null,
    //     ]);

    //     // Increment total registered participants
    //     $tournament->increment('registered_participants');

    //     // Build full invite URL to send back
    //     $frontendBase = 'https://www.markupdesigns.net/qec-web/tourmainpage/';
    //     //$frontendBase = 'http://localhost:5173/qec-web/tourmainpage/';
    //     $fullInviteUrl = $frontendBase . urlencode($tournament->name) . '?invite=' . $registration->invite_link;

    //     // Response
    //     return response()->json([
    //         'message' => 'Joined team successfully',
    //         'registration' => $registration,
    //         'invite_link' => $fullInviteUrl, // full URL for frontend
    //         'team_logo_url' => $team->team_logo ? asset('storage/' . $team->team_logo) : null,
    //     ]);
    // }
    
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
            ->where('is_captain', true) // Only captains have the invite link
            ->first();
    
        if (!$team) {
            return response()->json(['message' => 'Invalid invite link'], 404);
        }
    
        // Get the tournament
        $tournament = Tournament::findOrFail($team->tournament_id);
        
        // Check if user has any registration (any status) for this tournament
        $existingRegistration = TournamentRegistration::where('tournament_id', $tournament->id)
            ->where('user_id', $user->id)
            ->where('type', 'team')
            ->first();
    
        if ($existingRegistration) {
            // If status is 1, user is already actively registered
            if ($existingRegistration->status == 1) {
                // Check if they're already part of this specific team
                if ($existingRegistration->invite_link == $team->invite_link) {
                    return response()->json(['message' => 'Already part of this team'], 200);
                }
                return response()->json([
                    'message' => 'You have already registered in this tournament with another team'
                ], 422);
            }
            
            // If status is 0, reactivate the registration
            // Check if registration is still open
            if (Carbon::parse($tournament->registration_end)->lt(Carbon::now())) {
                return response()->json([
                    'message' => 'Registration is closed'
                ], 422);
            }
    
            // Check team capacity
            $membersCount = TournamentRegistration::where('invite_link', $team->invite_link)
                ->where('status', 1) // Only count active members
                ->count();
                
            if ($tournament->team_size && $membersCount >= $tournament->team_size) {
                return response()->json(['message' => 'Team is full'], 422);
            }
    
            // Update existing registration to active and join the new team
            $existingRegistration->update([
                'status' => 1,
                'invite_link' => $team->invite_link,
                'team_name' => $team->team_name,
                'team_tag' => $team->team_tag,
                'team_logo' => $team->team_logo,
                'is_captain' => false,
                'name' => $user->name ?? $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone ?? $user->mobile ?? null,
            ]);
    
            // Increment total registered participants
            $tournament->increment('registered_participants');
    
            // Build full invite URL
            $frontendBase = 'https://www.markupdesigns.net/qec-web/tourmainpage/';
            $fullInviteUrl = $frontendBase . urlencode($tournament->name) . '?invite=' . $team->invite_link;
    
            return response()->json([
                'message' => 'Team registration reactivated successfully',
                'registration' => $existingRegistration,
                'invite_link' => $fullInviteUrl,
                'team_logo_url' => $team->team_logo ? asset('storage/' . $team->team_logo) : null,
            ]);
        }
    
        // No existing registration - create new one
        // Check if registration is open
        if (Carbon::parse($tournament->registration_end)->lt(Carbon::now())) {
            return response()->json([
                'message' => 'Registration is closed'
            ], 422);
        }
    
        // Check team capacity
        $membersCount = TournamentRegistration::where('invite_link', $team->invite_link)
            ->where('status', 1) // Only count active members
            ->count();
            
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
            'invite_link' => $team->invite_link,
            'user_id' => $user->id,
            'name' => $user->name ?? $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone ?? $user->mobile ?? null,
            'status' => 1, // Explicitly set status
        ]);
    
        // Increment total registered participants
        $tournament->increment('registered_participants');
    
        // Build full invite URL
        $frontendBase = 'https://www.markupdesigns.net/qec-web/tourmainpage/';
        $fullInviteUrl = $frontendBase . urlencode($tournament->name) . '?invite=' . $registration->invite_link;
    
        return response()->json([
            'message' => 'Joined team successfully',
            'registration' => $registration,
            'invite_link' => $fullInviteUrl,
            'team_logo_url' => $team->team_logo ? asset('storage/' . $team->team_logo) : null,
        ]);
    }

    /**
     * My Tournaments API
     * GET /api/my-tournaments
     */


    //  public function myTournaments(Request $request)
    // {
    //     try {
    //         $userId = auth()->id();

    //         $tournaments = Tournament::with(['registrations', 'game'])
    //             ->whereHas('registrations', function ($q) use ($userId) {
    //                 $q->where('user_id', $userId);
    //             })
    //             ->orderByDesc('start_date')
    //             ->get();

    //         $response = $tournaments->map(function ($tournament) use ($userId) {

    //             // All registrations of this user for this tournament
    //             $userRegistrations = $tournament->registrations
    //                 ->where('user_id', $userId);

    //             // Map each registration individually
    //             return $userRegistrations->map(function ($registration) use ($tournament) {

    //                 $now = now();
    //                 if ($tournament->start_date > $now) {
    //                     $status = 'upcoming';
    //                 } elseif ($tournament->end_date && $tournament->end_date < $now) {
    //                     $status = 'completed';
    //                 } else {
    //                     $status = 'ongoing';
    //                 }

    //                  $inviteUrl = 'https://www.markupdesigns.net/qec-web/tourmainpage/'
    //                 //$inviteUrl = 'http://localhost:5173/qec-web/tourmainpage/'
    //                 . $tournament->title
    //                 . '?invite=' . $registration->invite_link;

    //                 return [
    //                     'tournament_id'   => $tournament->id,
    //                     'tournament_name' => $tournament->title,
    //                     'tournament_logo' => $tournament->logo
    //                         ? url('storage/' . $tournament->logo)
    //                         : null,

    //                     'game' => [
    //                         'id'   => $tournament->game->id ?? null,
    //                         'name' => $tournament->game->name ?? null,
    //                     ],

    //                     'prize'  => $tournament->prize_pool,
    //                     'date'   => [
    //                         'start' => $tournament->start_date,
    //                         'end'   => $tournament->end_date,
    //                     ],
    //                     'status' => $status,

    //                     'type'       => $registration->type,
    //                     'team_name'  => $registration->team_name,
    //                     'team_tag'   => $registration->team_tag,
    //                     'is_captain' => $registration->is_captain,
    //                     'invite_link'=> $inviteUrl,
    //                 ];
    //             });
    //         })->flatten(1); // Flatten because we have multiple registrations per tournament

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
    
    public function myTournaments(Request $request)
{
    try {
        $userId = auth()->id();

        $tournaments = Tournament::with(['registrations' => function ($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('status', 1); // ✅ Eager load only status 1 registrations
            }, 'game'])
            ->whereHas('registrations', function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->where('status', 1);
            })
            ->orderByDesc('start_date')
            ->get();

        $response = $tournaments->map(function ($tournament) use ($userId) {

            // All registrations are already filtered via eager loading
            $userRegistrations = $tournament->registrations;

            return $userRegistrations->map(function ($registration) use ($tournament) {

                $now = now();
                if ($tournament->start_date > $now) {
                    $status = 'upcoming';
                } elseif ($tournament->end_date && $tournament->end_date < $now) {
                    $status = 'completed';
                } else {
                    $status = 'ongoing';
                }

                $inviteUrl = 'https://www.markupdesigns.net/qec-web/tourmainpage/'
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
        })->flatten(1);

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
    
//   public function myTeams(Request $request)
// {
//     try {
//         $user = $request->user();
//         $userId = $user->id;

//         // Step 1: User ke saare teams
//         $teams = TournamentRegistration::with(['tournament.game'])
//             ->where('type', 'team')
//             ->where('user_id', $userId)
//             ->get();

//         if ($teams->isEmpty()) {
//             return response()->json([
//                 'status' => true,
//                 'data' => [
//                     'user_id' => $userId,
//                     'teams'   => []
//                 ]
//             ]);
//         }

//         // Step 2: Unique team identifiers
//         $teamKeys = $teams->map(function ($team) {
//             return [
//                 'tournament_id' => $team->tournament_id,
//                 'team_name'     => $team->team_name,
//             ];
//         });

//         // Step 3: All members of those teams (single query)
//         $membersGrouped = TournamentRegistration::with(['user.profile'])
//             ->where('type', 'team')
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

//         // Step 4: Build response
//         $teamsData = $teams->map(function ($team) use ($membersGrouped) {

//             $tournament = $team->tournament;
//             $groupKey   = $team->tournament_id . '_' . $team->team_name;
//             $members    = $membersGrouped[$groupKey] ?? collect();

//             // Tournament status
//             $now = now();
//             if ($tournament->start_date > $now) {
//                 $status = 'upcoming';
//             } elseif ($tournament->end_date && $tournament->end_date < $now) {
//                 $status = 'completed';
//             } else {
//                 $status = 'ongoing';
//             }

//             return [
//                 'team_id'   => $team->id,
//                 'team_name' => $team->team_name,
//                 'team_tag'  => $team->team_tag,
//                 'is_captain'=> $team->is_captain,
//                 'team_logo'=> $team->team_logo
//                     ? asset('storage/' . $team->team_logo)
//                     : null,

//                 // âœ… Team size
//                 'team_size' => $members->count(),

//                 // âœ… Team members
//                 'members' => $members->map(function ($member) {
//                     return [
//                         'user_id' => $member->user->id,
//                         'name'    => trim($member->user->first_name . ' ' . $member->user->last_name),
//                         'email'   => $member->user->email,
//                         'phone'   => $member->user->mobile,
//                         'profile_image' => optional($member->user->profile)->profile_image
//                             ? asset('storage/' . $member->user->profile->profile_image)
//                             : null,
//                     ];
//                 }),

//                 // Tournament info
//                 'tournament' => [
//                     'id'    => $tournament->id,
//                     'name'  => $tournament->title,
//                     'logo'  => $tournament->logo
//                         ? asset('storage/' . $tournament->logo)
//                         : null,
//                     'game' => [
//                         'id'   => $tournament->game->id ?? null,
//                         'name' => $tournament->game->name ?? null,
//                     ],
//                     'prize' => $tournament->prize_pool,
//                     'date' => [
//                         'start' => $tournament->start_date,
//                         'end'   => $tournament->end_date,
//                     ],
//                     'status' => $status,
//                 ],

//                 'invite_link' => $team->invite_link
//                     ? 'https://www.markupdesigns.net/qec-web/tourmainpage/' .
//                         rawurlencode($tournament->title) .
//                         '?invite=' . $team->invite_link
//                     : null,
//             ];
//         });

//         return response()->json([
//             'status' => true,
//             // 'data' => [
//                 // 'user_id' => $userId,
//                 'teams'   => $teamsData
//             // ]
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

        // Step 1: User ke saare teams (only status 1)
        $teams = TournamentRegistration::with(['tournament.game'])
            ->where('type', 'team')
            ->where('user_id', $userId)
            ->where('status', 1)
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

        // Step 3: All members of those teams with status 1 only
        $membersGrouped = TournamentRegistration::with(['user.profile'])
            ->where('type', 'team')
            ->where('status', 1)  // ✅ ADD THIS LINE - Only fetch members with status 1
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

                // Team size (only status 1 members)
                'team_size' => $members->count(),

                // Team members (only status 1)
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
                    ? 'https://www.markupdesigns.net/qec-web/tourmainpage/' .
                        rawurlencode($tournament->title) .
                        '?invite=' . $team->invite_link
                    : null,
            ];
        });

        return response()->json([
            'status' => true,
            'teams'   => $teamsData
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status'  => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
public function allTeams(Request $request)
{
    try {

        // ðŸ”¹ Sab team registrations with relations
        $registrations = TournamentRegistration::with([
                'tournament.game',
                'user.profile'
            ])
            ->where('type', 'team')
            ->get();

        if ($registrations->isEmpty()) {
            return response()->json([
                'status' => true,
                'tournaments' => []
            ]);
        }

        // ðŸ”¹ Tournament-wise group
        $tournaments = $registrations->groupBy('tournament_id')->map(function ($items) {

            $tournament = $items->first()->tournament;
            $now = now();

            // Tournament status
            if ($tournament->start_date > $now) {
                $status = 'upcoming';
            } elseif ($tournament->end_date && $tournament->end_date < $now) {
                $status = 'completed';
            } else {
                $status = 'ongoing';
            }

            // ðŸ”¹ Team-wise group inside tournament
            $teams = $items->groupBy('team_name')->map(function ($members) use ($tournament) {

                $team = $members->first();

                return [
                    'team_id'    => $team->id,
                    'team_name'  => $team->team_name,
                    'team_tag'   => $team->team_tag,
                    'team_logo'  => $team->team_logo
                        ? asset('storage/' . $team->team_logo)
                        : null,

                    'team_size' => $members->count(),

                    'members' => $members->map(function ($member) {
                        return [
                            'name'  => trim($member->user->first_name . ' ' . $member->user->last_name),
                            'email' => $member->user->email,
                            'phone' => $member->user->mobile,
                            'profile_image' => optional($member->user->profile)->profile_image
                                ? asset('storage/' . $member->user->profile->profile_image)
                                : null,
                        ];
                    })->values(),

                    'invite_link' => $team->invite_link
                        ? 'https://www.markupdesigns.net/qec-web/tourmainpage/' .
                            rawurlencode($tournament->title) .
                            '?invite=' . $team->invite_link
                        : null,
                ];
            })->values();

            return [
                'tournament_id' => $tournament->id,
                'tournament_name' => $tournament->title,
                'tournament_logo' => $tournament->logo
                    ? asset('storage/' . $tournament->logo)
                    : null,
                'game' => [
                    'id'   => $tournament->game->id ?? null,
                    'name' => $tournament->game->name ?? null,
                ],
                'prize_pool' => $tournament->prize_pool,
                'date' => [
                    'start' => $tournament->start_date,
                    'end'   => $tournament->end_date,
                ],
                'status' => $status,

                // âœ… All teams of this tournament
                'teams' => $teams
            ];
        })->values();

        return response()->json([
            'status' => true,
            'tournaments' => $tournaments
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    public function teamsByTournament(Request $request, $tournamentId)
    {
        try {
            // Fetch registrations with user, profile, AND social links
            $registrations = TournamentRegistration::with([
                'tournament.game',
                'user.profile',
                'user.socialLinks' //  this line
            ])
            ->where('tournament_id', $tournamentId)
            ->where('status', 1)
            ->get();
    
            if ($registrations->isEmpty()) {
                return response()->json([
                    'status' => true,
                    'teams' => [],
                    'teams_count' => 0,
                    'prize_pool' => 0
                ]);
            }
    
            $tournament = $registrations->first()->tournament;
            $now = now();
    
            // Tournament status logic
            if ($tournament->winner_team_id != null) {
                $status = 'completed';
            } elseif ($tournament->end_date && $tournament->end_date < $now) {
                $status = 'completed';
            } elseif ($tournament->start_date > $now) {
                $status = 'upcoming';
            } else {
                $status = 'ongoing';
            }
    
            // Group logic: For teams use team_name, for solo use user_id
            $teams = $registrations->groupBy(function ($item) {
                if ($item->type === 'team') {
                    return $item->team_name;
                }
                return 'solo_' . $item->user_id;
            })->map(function ($members) use ($tournament) {
                $first = $members->first();
    
                if ($first->type === 'team') {
                    $teamName = $first->team_name;
                    $teamTag  = $first->team_tag;
                    $teamLogo = $first->team_logo ? asset('storage/' . $first->team_logo) : null;
                    $inviteLink = $first->invite_link 
                        ? 'https://www.markupdesigns.net/qec-web/tourmainpage/' . rawurlencode($tournament->title) . '?invite=' . $first->invite_link 
                        : null;
                } else {
                    $user = $first->user;
                    $teamName = trim($user->first_name . ' ' . $user->last_name);
                    $teamTag  = 'Solo';
                    $teamLogo = null;
                    $inviteLink = null;
                }
    
                return [
                    'team_id'   => $first->id,
                    'team_name' => $teamName,
                    'team_tag'  => $teamTag,
                    'team_logo' => $teamLogo,
                    'team_size' => $members->count(),
                    'members' => $members->map(function ($member) {
                        $user = $member->user;
                        
                        // Get social links (if exists)
                        $socialLinks = [];
                        if ($user && $user->socialLinks) {
                            $socialLinks = array_filter([
                                'facebook'  => $user->socialLinks->facebook,
                                'instagram' => $user->socialLinks->instagram,
                                'twitter'   => $user->socialLinks->twitter,
                                'youtube'   => $user->socialLinks->youtube,
                                'discord'   => $user->socialLinks->discord,
                                'twitch'    => $user->socialLinks->twitch,
                            ]);
                        }
    
                        return [
                            'name'  => trim($user->first_name . ' ' . $user->last_name),
                            'email' => $user->email,
                            'phone' => $user->mobile,
                            'username' => $user->username,
                            'registration_phone' => $member->phone,
                            'profile_image' => optional($user->profile)->profile_image
                                ? asset('storage/' . $user->profile->profile_image)
                                : null,
                            'social_links' => $socialLinks, // Added social links
                        ];
                    })->values(),
                    'invite_link' => $inviteLink,
                    'type' => $first->type,
                ];
            })->values();
    
            $teams_count = $teams->count();
    
            return response()->json([
                'status' => true,
                'tournament' => [
                    'id'         => $tournament->id,
                    'name'       => $tournament->title,
                    'status'     => $status,
                    'prize_pool' => $tournament->prize_pool,
                    'teams_count'=> $teams_count
                ],
                'teams' => $teams
            ]);
    
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
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
                        ? 'https://www.markupdesigns.net/qec-web/tourmainpage/' .
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

    /**
     * Update team details (name, tag, logo) – Captain only
     * PUT /api/tournaments/{tournamentId}/team/update
     */
    public function updateTeam(Request $request, $tournamentId)
    {
        $user = $request->user();
    
        // DEBUG: See request data (remove after testing)
        \Log::info('UpdateTeam Request', [
            'all' => $request->all(),
            'files' => $request->allFiles(),
            'has_name' => $request->has('team_name'),
            'has_tag'  => $request->has('team_tag'),
            'has_file' => $request->hasFile('team_logo'),
        ]);
    
        // 1. Find captain
        $captainReg = TournamentRegistration::where('tournament_id', $tournamentId)
            ->where('user_id', $user->id)
            ->where('type', 'team')
            ->where('is_captain', true)
            ->first();
    
        if (!$captainReg) {
            return response()->json([
                'message' => 'You are not the captain of a team in this tournament.'
            ], 403);
        }
    
        // 2. Validate
        $validator = Validator::make($request->all(), [
            'team_name' => 'sometimes|required|string|max:191',
            'team_tag'  => 'sometimes|required|string|max:50',
            'team_logo' => 'nullable|image',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // 3. Get old team name (trim to avoid spaces)
        $oldTeamName = trim($captainReg->team_name);
        \Log::info('Old team name', ['old' => $oldTeamName]);
    
        // 4. Check duplicates (if changing)
        if ($request->has('team_name') || $request->has('team_tag')) {
            $newName = $request->team_name ?? $oldTeamName;
            $newTag  = $request->team_tag ?? $captainReg->team_tag;
    
            $duplicate = TournamentRegistration::where('tournament_id', $tournamentId)
                ->where('type', 'team')
                ->where(function ($q) use ($newName, $newTag, $oldTeamName) {
                    $q->where('team_name', $newName)
                      ->orWhere('team_tag', $newTag);
                })
                ->whereRaw('LOWER(TRIM(team_name)) != ?', [strtolower($oldTeamName)])
                ->exists();
    
            if ($duplicate) {
                return response()->json([
                    'message' => 'Another team already uses that name or tag.'
                ], 422);
            }
        }
    
        // 5. Handle logo
        $newLogoPath = $captainReg->team_logo;
        if ($request->hasFile('team_logo')) {
            if ($captainReg->team_logo && Storage::disk('public')->exists($captainReg->team_logo)) {
                Storage::disk('public')->delete($captainReg->team_logo);
            }
            $newLogoPath = $request->file('team_logo')->store('teams', 'public');
        }
    
        // 6. Build update data
        $updateData = [];
        if ($request->filled('team_name')) {
            $updateData['team_name'] = $request->team_name;
        }
        if ($request->filled('team_tag')) {
            $updateData['team_tag'] = $request->team_tag;
        }
        if ($request->hasFile('team_logo')) {
            $updateData['team_logo'] = $newLogoPath;
        }
    
        \Log::info('Update data', $updateData);
    
        // 7. Perform update with case‑insensitive trimming
        if (!empty($updateData)) {
            $affected = TournamentRegistration::where('tournament_id', $tournamentId)
                ->where('type', 'team')
                ->whereRaw('LOWER(TRIM(team_name)) = ?', [strtolower($oldTeamName)])
                ->update($updateData);
    
            \Log::info('Affected rows', ['affected' => $affected]);
        }
    
        // 8. Fetch updated team
        $newName = $updateData['team_name'] ?? $oldTeamName;
        $updatedTeam = TournamentRegistration::where('tournament_id', $tournamentId)
            ->where('type', 'team')
            ->whereRaw('LOWER(TRIM(team_name)) = ?', [strtolower($newName)])
            ->first();
    
        return response()->json([
            'message' => 'Team updated successfully',
            'team' => [
                'team_name' => $updatedTeam->team_name ?? $oldTeamName,
                'team_tag'  => $updatedTeam->team_tag ?? $captainReg->team_tag,
                'team_logo' => $updatedTeam && $updatedTeam->team_logo
                                ? asset('storage/' . $updatedTeam->team_logo)
                                : null,
            ]
        ]);
    }
    
    public function leaveTournament(Request $request, $tournamentId)
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
    
            // Find the tournament
            $tournament = Tournament::find($tournamentId);
            
            if (!$tournament) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tournament not found'
                ], 422);
            }
    
            // Check if tournament is published
            if ($tournament->visibility !== 'published') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tournament is not available'
                ], 400);
            }
    
            // Check if registration exists
            $registration = TournamentRegistration::where('tournament_id', $tournamentId)
                ->where('user_id', $user->id)
                ->where('status', 1) 
                ->first();
                
            // if (!$registration) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'You are not registered for this tournament'
            //     ], 404);
            // }
    
            // Check if tournament has already started
            $now = now();
            $tournamentStart = $tournament->start_date ? Carbon::parse($tournament->start_date) : null;
            
            // If tournament has already started, check if user has any prize or winnings
            if ($tournamentStart && $now->greaterThan($tournamentStart)) {
                // Check if user has any prize amount awarded
                if ($registration->prize_amount && $registration->prize_amount > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You cannot leave the tournament as you have already won a prize. Please contact admin for assistance.'
                    ], 400);
                }
                
                // Optional: Check if tournament has ended
                $tournamentEnd = $tournament->end_date ? Carbon::parse($tournament->end_date) : null;
                if ($tournamentEnd && $now->greaterThan($tournamentEnd)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tournament has already ended. You cannot leave now.'
                    ], 400);
                }
            }
    
            // Check if registration period is still open (only if tournament hasn't started)
            if (!$tournamentStart || $now->lessThan($tournamentStart)) {
                $registrationEnd = $tournament->registration_end ? Carbon::parse($tournament->registration_end) : null;
                
                if ($registrationEnd && $now->greaterThan($registrationEnd)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Registration period has ended. You cannot leave now.'
                    ], 400);
                }
            }
    
            // If it's a team registration, check if user is captain
            if ($registration->is_captain == 1 && $registration->type === 'team') {
                // Check if there are other team members
                $teamMembers = TournamentRegistration::where('tournament_id', $tournamentId)
                    ->where('team_name', $registration->team_name)
                    ->where('id', '!=', $registration->id)
                    ->where('status', 1)
                    ->count();
    
                if ($teamMembers > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are the captain of this team. Please ensure all team members leave first before you can leave.'
                    ], 400);
                }
            }
    
            // Check if user has any pending prize claims
            if ($registration->is_prize_claimed == 0 && $registration->prize_amount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have unclaimed prize amount. Please claim your prize before leaving.'
                ], 400);
            }
    
            // Soft delete or delete the registration
            // Option 1: Soft delete (recommended)
            $registration->status = 0; // Inactive
            $registration->save();
            
            // Option 2: Hard delete
            // $registration->delete();
    
            // Update tournament participant count
            $tournament->registered_participants = TournamentRegistration::where('tournament_id', $tournamentId)
                ->where('status', 1)
                ->count();
            $tournament->save();
    
            return response()->json([
                'success' => true,
                'message' => 'You have successfully left the tournament',
                'data' => [
                    'tournament_id' => $tournamentId,
                    'tournament_title' => $tournament->title,
                    'user_id' => $user->id,
                    'user_name' => $user->first_name . ' ' . $user->last_name,
                    'registration_status' => 'left'
                ]
            ]);
    
        } catch (\Exception $e) {
            \Log::error('Error in leaveTournament: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while leaving the tournament',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}