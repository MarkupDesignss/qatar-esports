<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    
    public function show(Request $request)
{
    try {
        $user = $request->user()->load('profile');

        return response()->json([
            'success' => true,
            'message' => 'Profile fetched successfully',
            'data' => [
                'first_name'    => $user->first_name,
                'last_name'     => $user->last_name,
                'email'         => $user->email,
                'mobile'        => $user->mobile,
                'profile_image' => $user->profile?->profile_image 
                    ? asset('storage/' . $user->profile->profile_image)
                    : null,
                'id_proof'      => $user->profile?->id_proof
                    ? asset('storage/' . $user->profile->id_proof)
                    : null,
            ]
        ], 200);

    } catch (\Throwable $th) {
        return response()->json([
            'success' => false,
            'message' => 'Unable to fetch profile',
            'error'   => $th->getMessage()
        ], 500);
    }
}

    public function update(Request $request)
    {
        try {
        $user = $request->user();

        $data = $request->validate([
            'first_name'    => 'required|string|max:191',
            'last_name'     => 'required|string|max:191',
            'email'         => 'required|email|max:191',
            'mobile'  => 'required|string|max:20',
            'profile_image' => 'nullable|image|max:2048',
            'id_proof'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        // 1. Update Users table
        $user->update([
            'first_name'   => $data['first_name'],
            'last_name'    => $data['last_name'],
            'email'        => $data['email'],
            'mobile' => $data['mobile'],
        ]);

        // 2. Update user_profiles table
        $profile = UserProfile::firstOrNew(['user_id' => $user->id]);

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')
                ->store('profiles', 'public');
        }

        if ($request->hasFile('id_proof')) {
            $data['id_proof'] = $request->file('id_proof')
                ->store('id_proofs', 'public');
        }

        $profile->user_id = $user->id;
        $profile->profile_image = $data['profile_image'] ?? $profile->profile_image;
        $profile->id_proof = $data['id_proof'] ?? $profile->id_proof;
        $profile->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data'    => [
                'user' => $user,
                'profile' => $profile,
            ],
        ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to update image',
                'data'    => $th->getMessage()
            ],500);
        }
    }
}

