<?php

use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentProposalMail;

it('sends appointment proposal mails when proposals are generated', function () {
    Mail::fake();

    [, , $task] = makeTaskWithProposals();

    Mail::assertQueued(AppointmentProposalMail::class, 3);
});
