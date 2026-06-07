<?php

namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Models\Shared\Item;
use App\Models\User;

class FoundItemPendingVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Item $item,
        public User $finder
    ) {
    }

 public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Lost Item Reported As Found'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.found-item-pending-verification'
        );
    }
}
