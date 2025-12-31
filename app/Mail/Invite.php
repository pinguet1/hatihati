<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Invite extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct() {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "You've been invited to join a group on Bayad, Bayad",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invite',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
