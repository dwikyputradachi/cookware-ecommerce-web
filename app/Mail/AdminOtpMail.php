<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class AdminOtpMail extends Mailable
{
    public string $otpCode;

    public function __construct(string $otpCode)
    {
        $this->otpCode = $otpCode;
    }

    public function build()
    {
        return $this->subject('Kode OTP Admin Murazon')
            ->view('emails.admin-otp');
    }
}