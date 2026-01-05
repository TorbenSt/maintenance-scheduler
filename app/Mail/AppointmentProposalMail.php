<?php

namespace App\Mail;

use App\Models\AppointmentProposal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class AppointmentProposalMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public AppointmentProposal $proposal
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Terminvorschlag für Ihre Wartung',
        );
    }

    public function content(): Content
    {
        $acceptUrl = URL::temporarySignedRoute(
            'public.proposals.accept',
            now()->addDays(14),
            ['token' => $this->proposal->token]
        );

        $rejectUrl = URL::temporarySignedRoute(
            'public.proposals.reject.form',
            now()->addDays(14),
            ['token' => $this->proposal->token]
        );

        return new Content(
            view: 'emails.appointment-proposal',
            with: [
                'proposal'  => $this->proposal,
                'acceptUrl' => $acceptUrl,
                'rejectUrl' => $rejectUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
