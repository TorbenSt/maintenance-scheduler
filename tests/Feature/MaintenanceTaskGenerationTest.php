<?php

use App\Models\Company;
use App\Models\Customer;
use App\Models\MaintenanceInterval;
use App\Models\MaintenanceContract;
use App\Models\MaintenanceTask;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('generates_the_next_task_automatically_when_a_task_is_completed', function () {

    // Arrange ------------------------------------------------------
    $company = Company::factory()->create();
    $customer = Customer::factory()->create(['company_id' => $company->id]);

    $interval = MaintenanceInterval::factory()->create([
        'company_id' => $company->id,
        'interval_months' => 12,
        'booking_window_days' => 30,
    ]);

    $contract = MaintenanceContract::factory()->create([
        'company_id' => $company->id,
        'customer_id' => $customer->id,
        'maintenance_interval_id' => $interval->id,
        'start_date' => '2025-03-01',
        'active' => true,
    ]);

    // Der erste Task wurde aus dem vorigen Test bereits automatisch erzeugt
    $firstTask = $contract->tasks()->first();

    expect($firstTask)->not->toBeNull();

    // Act: wir schließen den Task ab
    $firstTask->update(['status' => 'completed']);

    // Assert: ein neuer Task wurde erzeugt
    $nextTask = $contract->tasks()->where('id', '!=', $firstTask->id)->first();

    expect($nextTask)->not->toBeNull('Neuer Task wurde nicht automatisch erzeugt.');

    // Fälligkeitsdatum = alter due_date + interval_months
    expect($nextTask->due_date->toDateString())
        ->toBe($firstTask->due_date->copy()->addMonths($interval->interval_months)->toDateString());

    // Buchungsfenster korrekt
    expect($nextTask->booking_window_start->toDateString())
        ->toBe($nextTask->due_date->copy()->subDays($interval->booking_window_days)->toDateString());

    expect($nextTask->booking_window_end->toDateString())
        ->toBe($nextTask->due_date->toDateString());

    // Standardstatus
    expect($nextTask->status)->toBe('pending');
});


it('does_not_generate_a_new_task_when_contract_is_inactive', function () {
    $company = Company::factory()->create();
    $customer = Customer::factory()->create(['company_id' => $company->id]);

    $interval = MaintenanceInterval::factory()->create([
        'company_id' => $company->id,
        'interval_months' => 12,
    ]);

    $contract = MaintenanceContract::factory()->create([
        'company_id' => $company->id,
        'customer_id' => $customer->id,
        'maintenance_interval_id' => $interval->id,
        'start_date' => '2025-03-01',
        'active' => false,
    ]);

    $firstTask = $contract->tasks()->first();

    expect($firstTask)->not->toBeNull();

    // Act
    $firstTask->update(['status' => 'completed']);

    // Assert
    $tasks = $contract->tasks()->get();

    expect($tasks)->toHaveCount(1); // nur der erste Task existiert
});
