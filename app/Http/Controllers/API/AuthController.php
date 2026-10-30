<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * SIGNUP
     */
    public function signup(Request $request)
    {
        try {
            Log::info('Signup attempt', ['email' => $request->email]);

            $validated = $request->validate([
                'firstName'    => 'required|string|max:255',
                'lastName'     => 'nullable|string|max:255',
                'username'     => 'nullable|string|max:255',
                'email'        => 'required|email',
                'country_code' => 'nullable|string|max:10',
                'mobile'       => 'required|string|max:20',
                'password'     => 'required|min:6',
            ]);

            // 1. Email already exists?
            $existingUser = User::where('email', $validated['email'])->first();

            // Verified user → block
            if ($existingUser && $existingUser->email_verified) {
                Log::warning('Signup blocked - email already verified', ['email' => $validated['email']]);

                return response()->json([
                    'success' => false,
                    'message' => 'Email already registered. Please login.',
                    'errors'  => ['email' => ['Email already registered.']],
                ], 422);
            }

            // 2. Username kisi AUR ka na ho
            if (!empty($validated['username'])) {
                $usernameTaken = User::where('username', $validated['username'])
                    ->when($existingUser, fn($q) => $q->where('id', '!=', $existingUser->id))
                    ->exists();

                if ($usernameTaken) {
                    Log::warning('Signup blocked - username taken', ['username' => $validated['username']]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Username already taken.',
                        'errors'  => ['username' => ['Username already taken.']],
                    ], 422);
                }
            }

            // 3. Mobile kisi AUR ka na ho
            $mobileTaken = User::where('mobile', $validated['mobile'])
                ->when($existingUser, fn($q) => $q->where('id', '!=', $existingUser->id))
                ->exists();

            if ($mobileTaken) {
                Log::warning('Signup blocked - mobile taken', ['mobile' => $validated['mobile']]);

                return response()->json([
                    'success' => false,
                    'message' => 'Mobile number already registered.',
                    'errors'  => ['mobile' => ['Mobile already registered.']],
                ], 422);
            }

            $otp = rand(100000, 999999);

            $userData = [
                'first_name'     => $validated['firstName'],
                'last_name'      => $validated['lastName'] ?? null,
                'username'       => $validated['username'] ?? null,
                'email'          => $validated['email'],
                'country_code'   => $validated['country_code'] ?? null,
                'mobile'         => $validated['mobile'],
                'password'       => Hash::make($validated['password']),
                'email_verified' => 0,
                'status'         => 0,
                'otp'            => $otp,
                'otp_expires_at' => now()->addMinutes(10),
                'otp_sent_at'    => now(),
            ];

            if ($existingUser) {
                $existingUser->update($userData);
                $user = $existingUser;
                Log::info('Unverified user updated', ['user_id' => $user->id, 'email' => $user->email]);
            } else {
                $user = User::create($userData);
                Log::info('New user created', ['user_id' => $user->id, 'email' => $user->email]);
            }

            // Send OTP mail
            try {
                Mail::to($user->email)
                    ->send(new \App\Mail\EmailOtpMail($otp, $user->first_name));

                Log::info('Signup OTP mail sent', [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                    'otp'     => $otp,
                ]);
            } catch (\Throwable $mailError) {
                Log::error('Signup OTP mail FAILED', [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                    'error'   => $mailError->getMessage(),
                    'file'    => $mailError->getFile(),
                    'line'    => $mailError->getLine(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'OTP email could not be sent. Please try again.',
                    'error'   => $mailError->getMessage(),
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your email. Please verify.',
                'email'   => $user->email,
                'otp'     => app()->environment('local') ? $otp : null,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Signup validation failed', ['errors' => $e->errors()]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Signup FAILED', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Signup failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * VERIFY SIGNUP OTP
     */
    public function verifySignupOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'otp'   => 'required|digits:6',
            ]);

            Log::info('Verify signup OTP attempt', ['email' => $request->email]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                Log::warning('Verify OTP - user not found', ['email' => $request->email]);
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ], 404);
            }

            if ($user->email_verified) {
                Log::warning('Verify OTP - already verified', ['email' => $request->email]);
                return response()->json([
                    'success' => false,
                    'message' => 'Email already verified',
                ], 422);
            }

            if (!$user->otp || $user->otp !== $request->otp) {
                Log::warning('Verify OTP - invalid OTP', ['email' => $request->email]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP',
                ], 422);
            }

            if (!$user->otp_expires_at || now()->greaterThan($user->otp_expires_at)) {
                Log::warning('Verify OTP - OTP expired', ['email' => $request->email]);
                return response()->json([
                    'success' => false,
                    'message' => 'OTP expired',
                ], 422);
            }

            $user->update([
                'email_verified' => 1,
                'status'         => 1,
                'otp'            => null,
                'otp_expires_at' => null,
                'otp_sent_at'    => null,
            ]);

            Log::info('Email verified successfully', ['user_id' => $user->id]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Email verified successfully.',
                'token'   => $token,
                'user'    => $user->fresh(),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Verify signup OTP FAILED', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Verification failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * RESEND SIGNUP OTP
     */
    public function resendSignupOtp(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);

            Log::info('Resend signup OTP attempt', ['email' => $request->email]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                Log::warning('Resend OTP - user not found', ['email' => $request->email]);
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }

            if ($user->email_verified) {
                Log::warning('Resend OTP - already verified', ['email' => $request->email]);
                return response()->json(['success' => false, 'message' => 'Email already verified'], 422);
            }

            // Cooldown check (60 sec) — otp_sent_at se
            if ($user->otp_sent_at) {
                $secondsSinceSent = $user->otp_sent_at->diffInSeconds(now());

                Log::info('Resend OTP cooldown check', [
                    'email' => $request->email,
                    'seconds_since_sent' => $secondsSinceSent,
                ]);

                if ($secondsSinceSent < 60) {
                    $secondsLeft = 60 - $secondsSinceSent;

                    Log::warning('Resend OTP - cooldown active', [
                        'email' => $request->email,
                        'seconds_left' => $secondsLeft,
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => "Please wait {$secondsLeft} seconds before requesting a new OTP",
                    ], 429);
                }
            }

            $otp = rand(100000, 999999);

            $user->update([
                'otp'            => $otp,
                'otp_expires_at' => now()->addMinutes(10),
                'otp_sent_at'    => now(),
            ]);

            try {
                Mail::to($user->email)
                    ->send(new \App\Mail\EmailOtpMail($otp, $user->first_name));

                Log::info('Resend OTP mail sent', [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                    'otp'     => $otp,
                ]);
            } catch (\Throwable $mailError) {
                Log::error('Resend OTP mail FAILED', [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                    'error'   => $mailError->getMessage(),
                    'file'    => $mailError->getFile(),
                    'line'    => $mailError->getLine(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'OTP email could not be sent. Please try again.',
                    'error'   => $mailError->getMessage(),
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'OTP resent successfully',
                'otp'     => app()->environment('local') ? $otp : null,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Resend signup OTP FAILED', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Resend failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * LOGIN
     */
    // public function login(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'email'    => 'required|email',
    //             'password' => 'required'
    //         ]);

    //         Log::info('Login attempt', ['email' => $request->email]);

    //         $user = User::where('email', $request->email)->first();

    //         if (!$user) {
    //             Log::warning('Login - user not found', ['email' => $request->email]);
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Invalid email address'
    //             ], 422);
    //         }

    //         // Email verification check
    //         if (!$user->email_verified) {
    //             Log::warning('Login - email not verified', ['email' => $request->email]);
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Please verify your email first.',
    //             ], 422);
    //         }

    //         if ($user->status != 1) {
    //             Log::warning('Login - account inactive', ['email' => $request->email]);
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Account is inactive, kindly contact to admin.'
    //             ], 422);
    //         }

    //         if (!Hash::check($request->password, $user->password)) {
    //             Log::warning('Login - wrong password', ['email' => $request->email]);
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Incorrect password.'
    //             ], 422);
    //         }

    //         $token = $user->createToken('auth_token')->plainTextToken;

    //         Log::info('Login successful', ['user_id' => $user->id]);

    //         return response()->json([
    //             'success' => true,
    //             'token'   => $token,
    //             'user'    => $user
    //         ]);

    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation failed',
    //             'errors'  => $e->errors()
    //         ], 422);
    //     } catch (\Throwable $e) {
    //         Log::error('Login FAILED', [
    //             'message' => $e->getMessage(),
    //             'file'    => $e->getFile(),
    //             'line'    => $e->getLine(),
    //         ]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Login failed',
    //             'error'   => $e->getMessage(),
    //         ], 500);
    //     }
    // }
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required'
            ]);

            Log::info('Login attempt', ['email' => $request->email]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                Log::warning('Login - user not found', ['email' => $request->email]);
                return response()->json([
                    'success' => false,
                    'message' => 'User not found with this email address.'
                ], 404);
            }

            // Email verification check
            if (!$user->email_verified) {
                Log::warning('Login - email not verified', ['email' => $request->email]);
                return response()->json([
                    'success' => false,
                    'message' => 'Please verify your email first.',
                ], 422);
            }

            if ($user->status != 1) {
                Log::warning('Login - account inactive', ['email' => $request->email]);
                return response()->json([
                    'success' => false,
                    'message' => 'Account is inactive, kindly contact to admin.'
                ], 422);
            }

            if (!Hash::check($request->password, $user->password)) {
                Log::warning('Login - wrong password', ['email' => $request->email]);
                return response()->json([
                    'success' => false,
                    'message' => 'Incorrect password.'
                ], 422);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            Log::info('Login successful', ['user_id' => $user->id]);

            return response()->json([
                'success' => true,
                'token'   => $token,
                'user'    => $user
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Login FAILED', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Login failed',
            ], 500);
        }
    }

    /**
     * FORGOT PASSWORD (EMAIL OTP)
     */
    public function forgotPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            Log::info('Forgot password attempt', ['email' => $request->email]);

            $user = User::where('email', $request->email)
                ->where('status', 1)
                ->first();

            if (!$user) {
                Log::warning('Forgot password - user not found', ['email' => $request->email]);
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $otp = random_int(100000, 999999);

            $user->update([
                'otp'            => $otp,
                'otp_expires_at' => now()->addMinutes(10),
                'otp_sent_at'    => now(),
            ]);

            try {
                Mail::send('emails.admin-reset-otp', ['otp' => $otp], function ($message) use ($user) {
                    $message->to($user->email)
                            ->from('qataresports.mailgun.org@mg.markupdesigns.net', 'Qatar Esports')
                            ->subject('Qatar Esports Password Reset OTP');
                });

                Log::info('Forgot password OTP mail sent', [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                ]);
            } catch (\Throwable $mailError) {
                Log::error('Forgot password mail FAILED', [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                    'error'   => $mailError->getMessage(),
                    'file'    => $mailError->getFile(),
                    'line'    => $mailError->getLine(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'OTP email could not be sent. Please try again.',
                    'error'   => $mailError->getMessage(),
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to email'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Forgot password FAILED', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Request failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * VERIFY OTP
     */
    public function verifyOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'otp'   => 'required'
            ]);

            Log::info('Verify OTP attempt', ['email' => $request->email]);

            $user = User::where('email', $request->email)
                ->where('otp', $request->otp)
                ->where('otp_expires_at', '>=', now())
                ->first();

            if (!$user) {
                Log::warning('Verify OTP - invalid or expired', ['email' => $request->email]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired OTP'
                ], 400);
            }

            Log::info('OTP verified successfully', ['email' => $request->email]);

            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Verify OTP FAILED', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Verification failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * RESET PASSWORD
     */
    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'email'           => 'required|email',
                'password'        => 'required|min:6',
                'confirmPassword' => 'required|same:password'
            ]);

            Log::info('Reset password attempt', ['email' => $request->email]);

            $user = User::where('email', $request->email)
                ->whereNotNull('otp')
                ->where('otp_expires_at', '>=', now())
                ->first();

            if (!$user) {
                Log::warning('Reset password - OTP verification required', ['email' => $request->email]);
                return response()->json([
                    'success' => false,
                    'message' => 'OTP verification required'
                ], 400);
            }

            $user->update([
                'password'        => Hash::make($request->password),
                'otp'             => null,
                'otp_expires_at'  => null,
                'otp_sent_at'     => null,
            ]);

            Log::info('Password reset successful', ['user_id' => $user->id]);

            return response()->json([
                'success' => true,
                'message' => 'Password reset successful'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Reset password FAILED', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Reset failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET USER
     */
    public function getUser(Request $request)
    {
        try {
            $user = $request->user();
            return response()->json([
                'success' => true,
                'user'    => $user
            ]);
        } catch (\Illuminate\Contracts\Container\BindingResolutionException $e) {
            Log::error('Get user FAILED', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

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
        try {
            $request->user()->currentAccessToken()->delete();

            Log::info('Logout successful', ['user_id' => $request->user()->id]);

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ]);
        } catch (\Throwable $e) {
            Log::error('Logout FAILED', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}