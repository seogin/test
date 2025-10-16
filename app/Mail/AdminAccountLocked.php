<?php

namespace App\Mail;

use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminAccountLocked extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Admin $admin, public int $lockMinutes) {}

    public function build()
    {
        return $this->subject('Admin account locked')
            ->view('emails.admin_locked');
    }
}
