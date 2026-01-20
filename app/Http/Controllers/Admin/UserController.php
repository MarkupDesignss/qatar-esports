<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserController extends Controller
{
   public function users()
    {
        $users = User::orderBy('id', 'desc')
            ->paginate(10);

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
    
    // public function viewUser($id)
    // {
    //     $user = User::findOrFail($id);

    //     return view('admin.user.view', compact('user'));
    // }
    
        public function viewUser($id)
    {
        // User ke saath uske registrations aur tournaments ka data
        $user = User::with('tournamentRegistrations.tournament')->findOrFail($id);
        return view('admin.user.view', compact('user'));
    }
}