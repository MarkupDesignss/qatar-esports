<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use App\Models\UserSocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    /**
     * GET profile details + social links
     */
    public function show(Request $request)
    {
        try {
            $user = $request->user()->load('profile');
            $socialLinks = UserSocialLink::where('user_id', $user->id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Profile fetched successfully',
                'data' => [
                    'id'    => $user->id,
                    'first_name'    => $user->first_name,
                    'last_name'     => $user->last_name,
                    'email'         => $user->email,
                    'username'      => $user->username,
                    'country_code'      => $user->country_code,
                    'mobile'        => $user->mobile,
                    'profile_image' => $user->profile?->profile_image 
                        ? asset('storage/' . $user->profile->profile_image)
                        : null,
                    'id_proof' => $user->profile?->id_proof 
                        ? asset('storage/' . $user->profile->id_proof)
                        : null,
                    // Social links (nullable)
                    'facebook'      => $socialLinks?->facebook,
                    'instagram'     => $socialLinks?->instagram,
                    'twitter'       => $socialLinks?->twitter,
                    'youtube'       => $socialLinks?->youtube,
                    'discord'       => $socialLinks?->discord,
                    'twitch'        => $socialLinks?->twitch,
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

    /**
     * UPDATE profile + social links (ID proof removed)
     */
    public function update(Request $request)
    {
        try {
            $user = $request->user();

            // Validation – no id_proof, added social links (all nullable|url)
            $data = $request->validate([
                'first_name'    => 'required|string|max:191',
                'last_name'     => 'required|string|max:191',
                'email'         => 'required|email|max:191',
                'country_code' => 'nullable|string|max:10',
                'mobile'        => 'required|string|max:20',
                'username'        => 'required|string|max:191',
                'profile_image' => 'nullable|image|max:2048',
                'id_proof'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'facebook'      => 'nullable|url|max:255',
                'instagram'     => 'nullable|url|max:255',
                'twitter'       => 'nullable|url|max:255',
                'youtube'       => 'nullable|url|max:255',
                'discord'       => 'nullable|url|max:255',
                'twitch'        => 'nullable|url|max:255',
            ]);

            // 1. Update users table
            $user->update([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'country_code'     => $data['country_code'],
                'mobile'     => $data['mobile'],
                'username'     => $data['username'],
            ]);

            // 2. Update user_profiles table (only profile_image now)
            $profile = UserProfile::firstOrNew(['user_id' => $user->id]);

            if ($request->hasFile('profile_image')) {
                // Delete old image if exists
                if ($profile->profile_image && Storage::disk('public')->exists($profile->profile_image)) {
                    Storage::disk('public')->delete($profile->profile_image);
                }
                $profile->profile_image = $request->file('profile_image')->store('profiles', 'public');
            }
            
             // ID Proof
            if ($request->hasFile('id_proof')) {
                if ($profile->id_proof && Storage::disk('public')->exists($profile->id_proof)) {
                    Storage::disk('public')->delete($profile->id_proof);
                }
                $profile->id_proof = $request->file('id_proof')->store('id_proofs', 'public');
            }

            $profile->user_id = $user->id;
            $profile->save();

            // 3. Save social links (using UserSocialLink model)
            $socialData = array_filter($request->only([
                'facebook', 'instagram', 'twitter', 'youtube', 'discord', 'twitch'
            ]), function($value) {
                return !is_null($value);  // remove nulls, keep empty strings if needed? Better to keep null in DB.
            });

            // If all social fields are null/absent, you may delete the record or keep as is.
            // Here we updateOrCreate – will set null for fields not present.
            UserSocialLink::updateOrCreate(
                ['user_id' => $user->id],
                $socialData + [   // merge defaults (optional) – ensure missing fields become null
                    'facebook'  => $socialData['facebook'] ?? null,
                    'instagram' => $socialData['instagram'] ?? null,
                    'twitter'   => $socialData['twitter'] ?? null,
                    'youtube'   => $socialData['youtube'] ?? null,
                    'discord'   => $socialData['discord'] ?? null,
                    'twitch'    => $socialData['twitch'] ?? null,
                ]
            );

            // Reload fresh data for response
            $updatedProfile = UserProfile::where('user_id', $user->id)->first();
            $updatedSocial = UserSocialLink::where('user_id', $user->id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data'    => [
                    'user'    => $user,
                    'profile' => $updatedProfile,
                    'social'  => $updatedSocial,
                ],
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to update profile',
                'error'   => $th->getMessage()
            ], 500);
        }
    }
}