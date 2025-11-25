<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Company;
use App\Models\Customer;
use App\Models\MaintenanceTask;
use App\Models\Technician;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startsAt = $this->faker->dateTimeBetween('+1 day', '+30 days');
        $endsAt = (clone $startsAt)->modify('+1 hour');

        return [
            'company_id' => Company::factory(),
            'customer_id' => Customer::factory(),
            'maintenance_task_id' => MaintenanceTask::factory(),
            'technician_id' => Technician::factory(),
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => $this->faker->randomElement(['planned', 'confirmed', 'completed', 'cancelled']),
        ];
    }
}
