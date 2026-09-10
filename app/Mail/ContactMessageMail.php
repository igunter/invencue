<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessageMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    // Bail out of a hung SMTP connection instead of tying up the worker indefinitely.
    public int $timeout = 30;

    public int $tries = 3;

    public array $backoff = [30, 120, 300];

    public function __construct(
        public string $senderName,
        public string $senderEmail,
        public string $messageBody,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New contact form message — ' . config('app.name'),
            replyTo: [$this->senderEmail],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.contact-message');
    }
}
