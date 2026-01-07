<?php

namespace App\Mail;

use App\Models\MaintenanceTask;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;

class AppointmentProposalsMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;


    public function __construct(
        public MaintenanceTask $task,
        public Collection $proposals
    ) {}


    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Terminvorschläge für Ihre Wartung'
        );
    }

    public function content(): Content
    {
        $items = $this->proposals->map(function ($proposal) {
            return [
                'proposal' => $proposal,
                'acceptUrl' => URL::temporarySignedRoute(
                    'public.proposals.accept',
                    now()->addDays(14),
                    ['token' => $proposal->token]
                ),
                'rejectUrl' => URL::temporarySignedRoute(
                    'public.proposals.reject.form',
                    now()->addDays(14),
                    ['token' => $proposal->token]
                ),
            ];
        });

        return new Content(
            view: 'emails.appointment-proposals',
            with: [
                'task' => $this->task,
                'items' => $items,
            ],
        );
    }


    public function attachments(): array
    {
        return [];
    }
}
