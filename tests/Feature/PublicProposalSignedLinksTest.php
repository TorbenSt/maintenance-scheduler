<?php

use App\Models\Company;
use App\Models\Customer;
use App\Models\MaintenanceContract;
use App\Models\MaintenanceInterval;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;

uses(RefreshDatabase::class);

function setupProposal(): \App\Models\AppointmentProposal {
    $company = Company::factory()->create();
    $customer = Customer::factory()->create(['company_id' => $company->id]);

    $interval = MaintenanceInterval::factory()->create([
        'company_id' => $company->id,
        'interval_months' => 12,
        'booking_window_days' => 30,
        'estimated_duration_minutes' => 60,
    ]);

    $contract = MaintenanceContract::factory()->create([
        'company_id' => $company->id,
        'customer_id' => $customer->id,
        'maintenance_interval_id' => $interval->id,
        'start_date' => '2025-03-01',
        'active' => true,
    ]);

    $task = $contract->tasks()->first();
    $task->generateProposals(1);

    return $task->proposals()->first();
}

it('blocks unsigned accept link', function () {
    $proposal = setupProposal();

    $this->get("/p/{$proposal->token}/accept")
        ->assertForbidden(); // 403 durch signed middleware
});

it('allows signed accept link', function () {
    $proposal = setupProposal();

    $signed = URL::temporarySignedRoute(
        'public.proposals.accept',
        now()->addMinutes(10),
        ['token' => $proposal->token]
    );

    $this->get($signed)->assertOk();
});

it('blocks unsigned reject form link', function () {
    $proposal = setupProposal();

    $this->get("/p/{$proposal->token}/reject")
        ->assertForbidden();
});

it('allows signed reject form link', function () {
    $proposal = setupProposal();

    $signed = URL::temporarySignedRoute(
        'public.proposals.reject.form',
        now()->addMinutes(10),
        ['token' => $proposal->token]
    );

    $this->get($signed)->assertOk();
});

it('allows signed reject POST and keeps signature via query', function () {
    $proposal = setupProposal();

    $signedPostUrl = URL::temporarySignedRoute(
        'public.proposals.reject',
        now()->addMinutes(10),
        ['token' => $proposal->token]
    );

    $this->post($signedPostUrl, ['comment' => 'passt nicht'])
        ->assertOk();
});
