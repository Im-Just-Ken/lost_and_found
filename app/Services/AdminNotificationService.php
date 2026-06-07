<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

class AdminNotificationService
{
    public static function send($mailable): void
    {
        if (!config('admin_emails.send_admin_emails')) {
            return;
        }

        Mail::to(
            config('admin_emails.admin_email')
        )->send($mailable);
    }
}