<?php

namespace App\Http\Controllers;

use App\Models\AppointmentProposal;

class PublicProposalAcceptController extends Controller
{
    public function __invoke(string $token)
    {
        $proposal = AppointmentProposal::where('token', $token)->firstOrFail();

        // Wenn schon angenommen/abgelaufen -> freundlich reagieren
        if ($proposal->status === 'accepted') {
            return response('confirmed (already accepted)', 200);
        }

        if (in_array($proposal->status, ['expired'], true)) {
            return response('expired', 200);
        }

        $proposal->accept();

        return response('confirmed', 200);
    }
}
