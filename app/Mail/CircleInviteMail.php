<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CircleInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $circleName;
    public $inviteCode;
    public $ownerName;

    /**
     * Create a new message instance.
     */
    public function __construct($circleName, $inviteCode, $ownerName)
    {
        $this->circleName = $circleName;
        $this->inviteCode = $inviteCode;
        $this->ownerName = $ownerName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You are invited to join ' . $this->circleName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.circle.invite',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
