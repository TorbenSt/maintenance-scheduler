<?php

use App\Models\Company;
use App\Models\Customer;
use App\Models\MaintenanceContract;
use App\Models\MaintenanceInterval;
use App\Models\MaintenanceTask;
use App\Models\AppointmentProposal;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('accepts_an_appointment_proposal_and_confirms_the_task', function () {

    // Arrange ------------------------------------------------------
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

    // 3 Vorschläge erzeugen
    $task->generateProposals(3);

    $proposal = $task->proposals()->first(); // den ersten nehmen
    $token = $proposal->token;

    // Act ----------------------------------------------------------
    $proposal->accept();

    // Assert --------------------------------------------------------
    $proposal->refresh();
    expect($proposal->status)->toBe('accepted');

    // Ein Appointment muss entstanden sein
    $appointment = Appointment::where('maintenance_task_id', $task->id)->first();

    expect($appointment)->not->toBeNull();
    expect($appointment->customer_id)->toBe($customer->id);
    expect($appointment->company_id)->toBe($company->id);

    // Task muss bestätigt sein und appointment_id gesetzt
    $task->refresh();
    expect($task->status)->toBe('confirmed');
    expect($task->appointment_id)->toBe($appointment->id);

    // Alle anderen Proposals müssen expired sein
    $otherProposals = $task->proposals()->where('id', '!=', $proposal->id)->get();

    foreach ($otherProposals as $other) {
        expect($other->status)->toBe('expired');
    }
});
