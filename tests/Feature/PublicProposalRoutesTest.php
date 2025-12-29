<?php

use App\Models\Company;
use App\Models\Customer;
use App\Models\MaintenanceInterval;
use App\Models\MaintenanceContract;
use App\Models\Appointment;
use App\Models\CustomerResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

it('accepts a proposal via token route', function () {
    [, , $task] = makeTaskWithProposals();

    $proposal = $task->proposals()->first();

    $this->get("/p/{$proposal->token}/accept")
        ->assertOk()
        ->assertSee('confirmed');

    $task->refresh();
    expect($task->status)->toBe('confirmed');

    expect(Appointment::where('maintenance_task_id', $task->id)->exists())->toBeTrue();
});

it('shows a reject form via token route', function () {
    [, , $task] = makeTaskWithProposals();

    $proposal = $task->proposals()->first();

    $this->get("/p/{$proposal->token}/reject")
        ->assertOk()
        ->assertSee('Reject proposal')
        ->assertSee('comment');
});

it('rejects a proposal via token route and stores customer response', function () {
    [, , $task] = makeTaskWithProposals();

    $proposal = $task->proposals()->first();

    $this->post("/p/{$proposal->token}/reject", [
        'comment' => 'Bitte nächste Woche vormittags',
    ])->assertOk()
      ->assertSee('rejected');

    $proposal->refresh();
    expect($proposal->status)->toBe('rejected');

    expect(CustomerResponse::where('appointment_proposal_id', $proposal->id)->exists())->toBeTrue();
    expect(Appointment::where('maintenance_task_id', $task->id)->exists())->toBeFalse();
});

it('returns 404 for unknown token', function () {
    $this->get('/p/not-a-real-token/accept')->assertNotFound();
});
