<?php

namespace App\Http\Controllers;

use App\Models\AppointmentProposal;
use Illuminate\Http\Request;

class PublicProposalRejectController extends Controller
{
    public function __invoke(Request $request, string $token)
    {
        $proposal = AppointmentProposal::where('token', $token)->firstOrFail();

        // Validation minimal
        $data = $request->validate([
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        // Optional: wenn schon accepted → nicht mehr ablehnen
        if ($proposal->status === 'accepted') {
            return response('already accepted', 200);
        }

        if ($proposal->status === 'expired') {
            return response('expired', 200);
        }

        $proposal->reject($data['comment'] ?? null);

        return response('rejected', 200);
    }
}
