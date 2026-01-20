<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * SIGNUP
     */
    public function signup(Request $request)
    {
        try {
            $validated = $request->validate([
                'firstName' => 'required|string|max:255',
                'lastName'  => 'nullable|string|max:255',
                'username'  => 'nullable|string|unique:users,username',
                'email'     => 'required|email|unique:users,email',
                'mobile'    => 'required|string|unique:users,mobile',
                'password'  => 'required|min:6',
            ]);

            User::create([
                'first_name' => $validated['firstName'],
                'last_name'  => $validated['lastName'] ?? null,
                'username'   => $validated['username'] ?? null,
                'email'      => $validated['email'],
                'mobile'     => $validated['mobile'],
                'password'   => Hash::make($validated['password']),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Signup successful'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Signup failed'
            ], 500);
        }
    }

    /**
     * LOGIN
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)
            ->where('status', 1)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token'   => $token,
            'user'    => $user
        ]);
    }

    /**
     * FORGOT PASSWORD (EMAIL OTP)
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)
            ->where('status', 1)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $otp = random_int(100000, 999999);

        $user->update([
            'otp'            => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        Mail::raw(
            "Your password reset OTP is: {$otp}",
            fn ($msg) => $msg->to($user->email)->subject('Password Reset OTP')
        );

        return response()->json([
            'success' => true,
            'otp'            => $otp,
            'message' => 'OTP sent to email'
        ]);
    }

    /**
     * VERIFY OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required'
        ]);

        $user = User::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('otp_expires_at', '>=', now())
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully'
        ]);
    }

    /**
     * RESET PASSWORD
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'           => 'required|email',
            'password'        => 'required|min:6',
            'confirmPassword' => 'required|same:password'
        ]);

        $user = User::where('email', $request->email)
            ->whereNotNull('otp')
            ->where('otp_expires_at', '>=', now())
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'OTP verification required'
            ], 400);
        }

        $user->update([
            'password'        => Hash::make($request->password),
            'otp'             => null,
            'otp_expires_at'  => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password reset successful'
        ]);
    }

    /**
     * GET USER
     */

    public function getUser(Request $request)
    {
        try {
            $user = $request->user(); // returns authenticated user or null
            return response()->json([
                'success' => true,
                'user'    => $user
            ]);
        } catch (\Illuminate\Contracts\Container\BindingResolutionException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to resolve dependencies: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * LOGOUT
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}