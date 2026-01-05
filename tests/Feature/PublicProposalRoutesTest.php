<?php

use App\Models\Company;
use App\Models\Customer;
use App\Models\MaintenanceInterval;
use App\Models\MaintenanceContract;
use App\Models\Appointment;
use App\Models\CustomerResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;

uses(RefreshDatabase::class);

function makeTaskWithProposals(): array {
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
    $task->generateProposals(3);

    return [$company, $customer, $task];
}

it('accepts a proposal via signed token route', function () {
    [, , $task] = makeTaskWithProposals();

    $proposal = $task->proposals()->first();

    $url = URL::temporarySignedRoute(
        'public.proposals.accept',
        now()->addMinutes(10),
        ['token' => $proposal->token]
    );

    $this->get($url)
        ->assertOk()
        ->assertSee('confirmed');

    $task->refresh();
    expect($task->status)->toBe('confirmed');

    expect(Appointment::where('maintenance_task_id', $task->id)->exists())->toBeTrue();
});

it('shows a reject form via signed token route', function () {
    [, , $task] = makeTaskWithProposals();

    $proposal = $task->proposals()->first();

    $url = URL::temporarySignedRoute(
        'public.proposals.reject.form',
        now()->addMinutes(10),
        ['token' => $proposal->token]
    );

    $this->get($url)
        ->assertOk()
        ->assertSee('Termin passt nicht') // Text aus dem Formular
        ->assertSee('comment');
});

it('rejects a proposal via signed token route and stores customer response', function () {
    [, , $task] = makeTaskWithProposals();

    $proposal = $task->proposals()->first();

    $url = URL::temporarySignedRoute(
        'public.proposals.reject',
        now()->addMinutes(10),
        ['token' => $proposal->token]
    );

    $this->post($url, [
        'comment' => 'Bitte nächste Woche vormittags',
    ])->assertOk()
      ->assertSee('rejected');

    $proposal->refresh();
    expect($proposal->status)->toBe('rejected');

    expect(CustomerResponse::where('appointment_proposal_id', $proposal->id)->exists())->toBeTrue();
    expect(Appointment::where('maintenance_task_id', $task->id)->exists())->toBeFalse();
});

it('returns 404 for unknown token (with signed link)', function () {
    $url = URL::temporarySignedRoute(
        'public.proposals.accept',
        now()->addMinutes(10),
        ['token' => 'not-a-real-token']
    );

    $this->get($url)->assertNotFound();
});
