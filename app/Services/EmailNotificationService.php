<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class EmailNotificationService
{
    public static function sendToUser(
        User $user,
        Mailable $mail
    ): void {

        $recipient = config('admin_emails.use_local_admin_email')
            ? config('admin_emails.admin_email')
            : $user->email;

        Mail::to($recipient)->send($mail);
    }
}