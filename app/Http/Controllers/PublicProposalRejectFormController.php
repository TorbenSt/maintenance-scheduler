<?php

namespace App\Http\Controllers;

use App\Models\AppointmentProposal;

class PublicProposalRejectFormController extends Controller
{
    public function __invoke(string $token)
    {
        $proposal = AppointmentProposal::where('token', $token)->firstOrFail();

        return view('public.proposals.reject', [
            'proposal' => $proposal,
        ]);
    }
}
