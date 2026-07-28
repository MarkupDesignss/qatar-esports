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
        // 1. Log incoming request
        Log::info('Contact API - Request received', [
            'ip'       => $request->ip(),
            'user_agent' => $request->userAgent(),
            'data'     => $request->except(['message']) // exclude sensitive message content from log
        ]);
    
        // 2. Validate request
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'subject'   => 'required|string|max:255',
            'message'   => 'required|string',
        ]);
    
        Log::info('Contact API - Validation passed', ['email' => $validated['email']]);
    
        try {
            // 3. Save to DB
            $contact = ContactRequest::create($validated);
            Log::info('Contact API - Saved to DB', ['contact_id' => $contact->id]);
    
            // 4. Send email to Admin
            Mail::to(env('MAIL_FROM_ADDRESS'))->send(new ContactUsAdminMail($validated));
            Log::info('Contact API - Admin email sent', ['to' => env('MAIL_FROM_ADDRESS'), 'subject' => $validated['subject']]);
    
            // 5. Success response
            return response()->json([
                'status'  => true,
                'message' => 'Your request has been submitted successfully.'
            ], 201);
    
        } catch (\Throwable $e) {
            // 6. Log full exception details
            Log::error('Contact API - Error occurred', [
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'request_data' => $validated ?? null
            ]);
    
            return response()->json([
                'status'  => false,
                'message' => 'Unable to process your request because Mailgun is currently deactivated. Please contact support.'
            ], 500);
        }
    }
}