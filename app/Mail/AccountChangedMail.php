<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountChangedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @param 'email'|'password' $type
     */
    public function __construct(
        public User $user,
        public string $type,
        public ?string $oldEmail = null,
        public ?string $newEmail = null,
    ) {
    }

    public function envelope(): Envelope
    {
        $subject = $this->type === 'email'
            ? 'Your ' . config('app.name') . ' email address was changed'
            : 'Your ' . config('app.name') . ' password was changed';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.account-changed');
    }
}
