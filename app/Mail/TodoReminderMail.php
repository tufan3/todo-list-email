<?php

namespace App\Mail;

use App\Models\Todo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class TodoReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $todo;
    public $csvPath;

    /**
     * Create a new message instance.
     */
    public function __construct(Todo $todo, string $csvPath)
    {
        $this->todo = $todo;
        $this->csvPath = $csvPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Todo Reminder Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.todo-reminder',
            with: [
                'todo' => $this->todo,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromStorage($this->csvPath)
                ->as('posts.csv')
                ->withMime('text/csv'),
        ];
    }
}
