<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\ContactRequest;


class ContactStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;

    public function __construct(ContactRequest $contact)
    {
        $this->contact = $contact;
    }

    public function build()
    {
        return $this->subject('Your Contact Request Status Has Been Updated')
            ->view('emails.contact-status');
    }
}
