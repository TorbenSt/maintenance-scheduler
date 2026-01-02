<?php

namespace App\Http\Controllers;

use App\Models\AppointmentProposal;

class PublicProposalRejectFormController extends Controller
{
    public function __invoke(string $token)
    {
        $proposal = AppointmentProposal::where('token', $token)->firstOrFail();

        // Minimaler MVP: Später ersetzen durch Volt/Livewire.
        $html = <<<HTML
            <!doctype html>
            <html>
            <head><meta charset="utf-8"><title>Reject</title></head>
            <body>
            <h1>Reject proposal</h1>
            <form method="POST" action="/p/{$proposal->token}/reject">
                <input type="hidden" name="_token" value="{$this->csrfToken()}">
                <label>comment</label><br>
                <textarea name="comment" rows="4" cols="40"></textarea><br><br>
                <button type="submit">Send</button>
            </form>
            </body>
            </html>
        HTML;

        return response($html, 200);
    }

    private function csrfToken(): string
    {
        return csrf_token();
    }
}
