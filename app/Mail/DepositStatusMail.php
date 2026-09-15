<?php

namespace App\Mail;

use App\Models\Deposit;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DepositStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Deposit $deposit,
        public string $event = 'created'
    ) {}

    public function envelope(): Envelope
    {
        $statusText = match ($this->event) {
            'approved' => 'Approved & Credited!',
            'rejected' => 'Rejected',
            default => 'Request Received',
        };

        return new Envelope(
            subject: "DexTrade Deposit Update - \${$this->deposit->amount} USDT Deposit {$statusText}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.deposit_status',
        );
    }
}
