<?php

use App\Mail\AppointmentProposalsMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

it('queues exactly one mail with multiple proposals for a task', function () {
    Mail::fake();

    [, $customer, $task] = makeTaskWithProposals(); // erzeugt 3 proposals

    // Act: neue Methode, die 1 Mail bündelt
    $task->sendProposalEmail();

    // Assert: genau 1 Mail
    Mail::assertQueued(AppointmentProposalsMail::class, 1);

    // Prüfen ob Adresse richtig und ob 3 proposals enthalten sind
    Mail::assertQueued(AppointmentProposalsMail::class, function ($mail) use ($customer, $task) {
        return $mail->hasTo($customer->email)
            && $mail->task->is($task)
            && $mail->proposals->count() === 3;
    });
});
