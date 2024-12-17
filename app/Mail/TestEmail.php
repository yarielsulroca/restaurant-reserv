<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Test Email from Restaurant App',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.test',
        );
    }
}
