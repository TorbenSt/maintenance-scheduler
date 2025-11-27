<?php

use App\Models\Company;
use App\Models\Customer;
use App\Models\MaintenanceInterval;
use App\Models\MaintenanceContract;
use App\Models\MaintenanceTask;
use App\Models\AppointmentProposal;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('generates_appointment_proposals_for_a_task', function () {

    // Arrange -------------------------------------------------------------
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

    expect($task)->not->toBeNull();

    // Act: generiere 3 Vorschläge ----------------------------------------
    $task->generateProposals(3);

    // Assert --------------------------------------------------------------
    $proposals = AppointmentProposal::where('maintenance_task_id', $task->id)->get();

    expect($proposals)->toHaveCount(3);

    foreach ($proposals as $proposal) {
        expect($proposal->token)->not->toBeNull();
        expect($proposal->token)->toBeString();

        expect($proposal->proposed_starts_at)->not->toBeNull();
        expect($proposal->proposed_ends_at)->not->toBeNull();

        expect($proposal->status)->toBe('pending');

        // korrekte Verknüpfungen
        expect($proposal->company_id)->toBe($company->id);
        expect($proposal->customer_id)->toBe($customer->id);
    }

    // Task muss jetzt Status "proposed" haben
    $task->refresh();
    expect($task->status)->toBe('proposed');
});
