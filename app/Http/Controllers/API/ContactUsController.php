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
            $contact = ContactRequest::create($validated);

            // Send email to Admin (Queue recommended)
            Mail::to(config('mail.support_email'))
                ->send(new ContactUsAdminMail($validated));

            // Success response
            return response()->json([
                'status'  => true,
                'message' => 'Your request has been submitted successfully.'
            ], 201);

        } catch (\Exception $e) {

            // Log error (important)
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