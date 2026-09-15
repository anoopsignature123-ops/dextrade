<?php

namespace App\Mail;

use App\Models\User;
use App\Models\UserPackage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PackagePurchasedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public UserPackage $userPackage
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'DexTrade Investment Confirmed - $'.number_format($this->userPackage->invested_amount, 2).' Package Activated!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.package_purchased',
        );
    }
}
