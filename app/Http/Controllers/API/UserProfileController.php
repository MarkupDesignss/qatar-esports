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
                    'id'                => $user->id,
                    'first_name'        => $user->first_name,
                    'last_name'         => $user->last_name,
                    'email'             => $user->email,
                    'pending_email'     => $user->pending_email,
                    'is_email_verified' => (bool) $user->email_verified,
                    'username'          => $user->username,
                    'country_code'      => $user->country_code,
                    'mobile'            => $user->mobile,
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
     * UPDATE profile + social links
     * On Email change OTP send
     */
    public function update(Request $request)
    {
        try {
            $user = $request->user();

            // Validation
            $data = $request->validate([
                'first_name'    => 'required|string|max:191',
                'last_name'     => 'required|string|max:191',
                'email'         => 'required|email|max:191|unique:users,email,' . $user->id,
                'country_code'  => 'nullable|string|max:10',
                'mobile'        => 'required|string|max:20',
                'username'      => 'required|string|max:191|unique:users,username,' . $user->id,
                'profile_image' => 'nullable',
                'id_proof'      => 'nullable',
                'facebook'      => 'nullable|url|max:255',
                'instagram'     => 'nullable|url|max:255',
                'twitter'       => 'nullable|url|max:255',
                'youtube'       => 'nullable|url|max:255',
                'discord'       => 'nullable|url|max:255',
                'twitch'        => 'nullable|url|max:255',
            ]);

            $emailChanged = $user->email !== $data['email'];

            // 1. Update users table
            $updateData = [
                'first_name'   => $data['first_name'],
                'last_name'    => $data['last_name'],
                'country_code' => $data['country_code'],
                'mobile'       => $data['mobile'],
                'username'     => $data['username'],
            ];

            $otp = null;

            if ($emailChanged) {
                $otp = rand(100000, 999999);

                $updateData['pending_email']  = $data['email'];
                $updateData['otp']            = $otp;
                $updateData['otp_expires_at'] = now()->addMinutes(10);
            }

            $user->update($updateData);

            if ($emailChanged) {
                try {
                    \Illuminate\Support\Facades\Mail::to($user->pending_email)
                        ->send(new \App\Mail\EmailOtpMail($otp, $user->first_name));
                } catch (\Throwable $mailError) {
                    \Illuminate\Support\Facades\Log::error('OTP mail failed: ' . $mailError->getMessage());
                }
            }

            // Update user_profiles table
            $profile = UserProfile::firstOrNew(['user_id' => $user->id]);

            if ($request->hasFile('profile_image')) {
                if ($profile->profile_image && Storage::disk('public')->exists($profile->profile_image)) {
                    Storage::disk('public')->delete($profile->profile_image);
                }
                $profile->profile_image = $request->file('profile_image')->store('profiles', 'public');
            }

            if ($request->hasFile('id_proof')) {
                if ($profile->id_proof && Storage::disk('public')->exists($profile->id_proof)) {
                    Storage::disk('public')->delete($profile->id_proof);
                }
                $profile->id_proof = $request->file('id_proof')->store('id_proofs', 'public');
            }

            $profile->user_id = $user->id;
            $profile->save();

            // Save social links
            $socialData = array_filter($request->only([
                'facebook', 'instagram', 'twitter', 'youtube', 'discord', 'twitch'
            ]), function ($value) {
                return !is_null($value);
            });

            UserSocialLink::updateOrCreate(
                ['user_id' => $user->id],
                $socialData + [
                    'facebook'  => $socialData['facebook']  ?? null,
                    'instagram' => $socialData['instagram'] ?? null,
                    'twitter'   => $socialData['twitter']   ?? null,
                    'youtube'   => $socialData['youtube']   ?? null,
                    'discord'   => $socialData['discord']   ?? null,
                    'twitch'    => $socialData['twitch']    ?? null,
                ]
            );

            // Reload fresh data
            $updatedProfile = UserProfile::where('user_id', $user->id)->first();
            $updatedSocial  = UserSocialLink::where('user_id', $user->id)->first();

            return response()->json([
                'success'       => true,
                'message'       => $emailChanged
                    ? 'Profile updated. OTP sent to your new email. Please verify to complete the change.'
                    : 'Profile updated successfully',
                'email_changed' => $emailChanged,
                'pending_email' => $emailChanged ? $user->fresh()->pending_email : null,
                'otp'           => $otp,
                'data'    => [
                    'user'    => $user->fresh(),
                    'profile' => $updatedProfile,
                    'social'  => $updatedSocial,
                ],
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to update profile',
                'error'   => $th->getMessage()
            ], 500);
        }
    }

    /**
     * VERIFY email OTP
     */
    public function verifyEmailOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = $request->user();

        if (!$user->pending_email) {
            return response()->json([
                'success' => false,
                'message' => 'No pending email change request',
            ], 422);
        }

        if (!$user->otp || $user->otp !== $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP',
            ], 422);
        }

        if (!$user->otp_expires_at || now()->greaterThan($user->otp_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired',
            ], 422);
        }
        
        $user->update([
            'email'          => $user->pending_email,
            'pending_email'  => null,
            'email_verified' => 1,
            'otp'            => null,
            'otp_expires_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Email verified and profile updated successfully.',
            'data'    => [
                'email' => $user->fresh()->email,
            ],
        ]);
    }
    
    /**
     * RESEND email OTP for pending email change
     */
    public function resendEmailOtp(Request $request)
    {
        try {
            $user = $request->user();
    
            // 1. Check pending email change request exists
            if (!$user->pending_email) {
                return response()->json([
                    'success' => false,
                    'message' => 'No pending email change request found',
                ], 422);
            }
    
            // 2. Cooldown check (60 seconds) — spam prevent
            if ($user->otp_expires_at && $user->otp_expires_at->gt(now()->addMinutes(9))) {
                $secondsLeft = now()->diffInSeconds($user->otp_expires_at->subMinutes(9));
                return response()->json([
                    'success' => false,
                    'message' => "Please wait {$secondsLeft} seconds before requesting a new OTP",
                ], 429);
            }
    
            $otp = rand(100000, 999999);
    
            $user->update([
                'otp'            => $otp,
                'otp_expires_at' => now()->addMinutes(10),
            ]);
    
            // 4. Mail bhejo
            try {
                \Illuminate\Support\Facades\Mail::to($user->pending_email)
                    ->send(new \App\Mail\EmailOtpMail($otp, $user->first_name));
            } catch (\Throwable $mailError) {
                \Illuminate\Support\Facades\Log::error('Resend OTP mail failed: ' . $mailError->getMessage());
            }
    
            return response()->json([
                'success'       => true,
                'message'       => 'OTP resent successfully to your new email',
                'pending_email' => $user->pending_email,
                'otp'           => app()->environment('local') ? $otp : null, // testing ke liye
            ]);
    
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to resend OTP',
                'error'   => $th->getMessage(),
            ], 500);
        }
    }
}