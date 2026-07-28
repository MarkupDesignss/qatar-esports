<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Matchs;
use Carbon\Carbon;

class UserController extends Controller
{ 
    public function users(Request $request)
    {
        $query = User::query();

        // Apply search filter if provided
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('first_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('username', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('mobile', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('country_code', 'LIKE', "%{$searchTerm}%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$searchTerm}%"]);
            });
        }

        $users = $query->orderBy('id', 'desc')
            ->paginate(10)
            ->appends(request()->query()); // Preserves search query in pagination

        return view('admin.user.index', compact('users'));
    }


    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'status' => !$user->status
        ]);

        return back()->with('success', 'Status updated.');
    }
    
    public function viewUser($id)
    {
        $user = User::with([
            'tournamentRegistrations.tournament',
            'profile',
            'socialLinks'
        ])->findOrFail($id);
    
        //dd($user);
        return view('admin.user.view', compact('user'));
    }
    public function destroy(User $user)
    {
        DB::beginTransaction();
    
        try {
    
            // Delete Tournament Registrations
            $user->tournamentRegistrations()->delete();
    
            // Delete Profile
            $user->profile()->delete();
    
            // Delete Social Links
            $user->socialLinks()->delete();
    
            // Delete Matches
            Matchs::where('team1_id', $user->id)
                ->orWhere('team2_id', $user->id)
                ->orWhere('winner_id', $user->id)
                ->delete();
    
            // Delete User
            $user->delete();
    
            DB::commit();
    
            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User deleted successfully.');
    
        } catch (\Exception $e) {
    
            DB::rollBack();
    
            return back()->with('error', $e->getMessage());
        }
    }
}