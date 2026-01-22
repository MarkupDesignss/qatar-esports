<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use App\Mail\ContactUsAdminMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactUsController extends Controller
{
    /**
     * Store Contact Us request
     */
    public function store(Request $request)
    {
        // 1Validate request
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'subject'   => 'required|string|max:255',
            'message'   => 'required|string',
        ]);

        try {
            // Save to DB
            ContactRequest::create($validated);

            // Send email to Admin (Mailgun-safe)
            Mail::to(env('MAIL_FROM_ADDRESS'))
                ->send(new ContactUsAdminMail($validated));

            // Success response
            return response()->json([
                'status'  => true,
                'message' => 'Your request has been submitted successfully.'
            ], 201);

        } catch (\Throwable $e) {

            // Log error
            Log::error('Contact Us API Error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }
}