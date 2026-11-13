<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GreetingMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public array $files;

    public string $htmlContent;

    public string $mailSubject;

    public function __construct(string $htmlContent, array $files = [], string $mailSubject = 'Greeting Mail')
    {
        $this->htmlContent = $htmlContent;
        $this->files = $files;
        $this->mailSubject = $mailSubject ?: 'Greeting Mail';
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: $this->mailSubject,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function build()
    {
        return $this
            ->subject($this->mailSubject)
            ->html($this->htmlContent);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        $attachments = [];

        foreach ($this->files as $file) {
            $attachments[] = Attachment::fromPath($file->getRealPath())
                ->as($file->getClientOriginalName())
                ->withMime($file->getMimeType());
        }

        return $attachments;
    }
}
