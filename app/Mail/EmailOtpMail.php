<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $name;

    public function __construct($otp, $name = 'User')
    {
        $this->otp  = $otp;
        $this->name = $name;
    }

    public function build()
    {
        return $this->subject('Verify Your Email - OTP')
                    ->view('emails.email_otp');
    }
}