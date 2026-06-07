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


class FoundReportApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
  public function __construct(
        public Item $item,
        public User $finder
    ) {}

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this
            ->subject('Your Found Item Report Has Been Approved')
            ->view('emails.found-report-approved');
    }
}
