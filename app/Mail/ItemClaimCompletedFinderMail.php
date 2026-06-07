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

class ItemClaimCompletedFinderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
   public function __construct(
        public Item $item,
        public User $finder,
    ) {}

    public function build()
    {
        return $this
            ->subject('Thank You for Returning a Lost Item')
            ->view('emails.item-claim-completed-finder');
    }
}
