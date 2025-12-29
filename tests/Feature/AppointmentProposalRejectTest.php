<?php

use App\Models\Company;
use App\Models\Customer;
use App\Models\MaintenanceContract;
use App\Models\MaintenanceInterval;
use App\Models\Appointment;
use App\Models\CustomerResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('rejects_an_appointment_proposal_and_stores_a_customer_response', function () {

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

    $proposal = $task->proposals()->first();

    // Act
    $proposal->reject('Dienstagvormittag passt besser');

    // Assert: Proposal rejected
    $proposal->refresh();
    expect($proposal->status)->toBe('rejected');

    // CustomerResponse wurde gespeichert
    $response = CustomerResponse::where('appointment_proposal_id', $proposal->id)->first();
    expect($response)->not->toBeNull();
    expect($response->response)->toBe('rejected');
    expect($response->comment)->toBe('Dienstagvormittag passt besser');

    // Task bleibt proposed, weil es noch andere Vorschläge gibt
    $task->refresh();
    expect($task->status)->toBe('proposed');

    // Kein Appointment angelegt
    expect(Appointment::where('maintenance_task_id', $task->id)->exists())->toBeFalse();
});

it('sets_task_back_to_pending_if_all_proposals_are_rejected_or_expired', function () {

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
    $task->generateProposals(2);

    // Act: beide ablehnen
    foreach ($task->proposals as $proposal) {
        $proposal->reject('Keiner passt');
    }

    // Assert
    $task->refresh();
    expect($task->status)->toBe('pending');
    expect($task->appointment_id)->toBeNull();
});
