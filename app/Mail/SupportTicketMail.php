<?php

namespace App\Mail;

use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SupportTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public SupportTicket $ticket,
        public string $event = 'created',
        public ?string $messageText = null
    ) {}

    public function envelope(): Envelope
    {
        $subjectText = match ($this->event) {
            'admin_reply' => "New Admin Response on Support Ticket #{$this->ticket->ticket_number}",
            default => "Support Ticket #{$this->ticket->ticket_number} Created Successfully",
        };

        return new Envelope(
            subject: "DexTrade Support - {$subjectText}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.support_ticket',
        );
    }
}
