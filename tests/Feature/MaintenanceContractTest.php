<?php

use App\Models\Company;
use App\Models\Customer;
use App\Models\MaintenanceInterval;
use App\Models\MaintenanceContract;
use App\Models\MaintenanceTask;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a maintenance task automatically when a contract is created', function () {

    // Arrange -----------------------------------------------------
    $company = Company::factory()->create();
    $customer = Customer::factory()->create(['company_id' => $company->id]);

    $interval = MaintenanceInterval::factory()->create([
        'company_id' => $company->id,
        'name' => 'Heizungswartung',
        'interval_months' => 12,
        'booking_window_days' => 30,
        'estimated_duration_minutes' => 60,
    ]);

    $startDate = now()->setDate(2025, 3, 1);

    // Act ----------------------------------------------------------
    $contract = MaintenanceContract::create([
        'company_id' => $company->id,
        'customer_id' => $customer->id,
        'maintenance_interval_id' => $interval->id,
        'start_date' => $startDate,
        'active'    => true,
    ]);

    // Assert -------------------------------------------------------
    expect($contract)->not->toBeNull();

    // Task wurde automatisch erzeugt?
    $task = MaintenanceTask::where('maintenance_contract_id', $contract->id)->first();

    expect($task)->not->toBeNull('MaintenanceTask wurde nicht automatisch erzeugt.');

    // Prüfe due_date (erstes Intervall = Startdatum + interval_months)
    expect($task->due_date->toDateString())
        ->toBe($startDate->copy()->addMonths($interval->interval_months)->toDateString());

    // Booking Window Start = due_date - booking_window_days
    expect($task->booking_window_start->toDateString())
        ->toBe($task->due_date->copy()->subDays($interval->booking_window_days)->toDateString());

    // Booking Window End = due_date
    expect($task->booking_window_end->toDateString())
        ->toBe($task->due_date->toDateString());

    // Status
    expect($task->status)->toBe('pending');
});
