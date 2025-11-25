<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Company;
use App\Models\Customer;
use App\Models\MaintenanceTask;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AppointmentProposal>
 */
class AppointmentProposalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $proposedStartsAt = $this->faker->dateTimeBetween('+1 day', '+30 days');
        $proposedEndsAt = (clone $proposedStartsAt)->modify('+1 hour');

        return [
            'company_id' => Company::factory(),
            'maintenance_task_id' => MaintenanceTask::factory(),
            'customer_id' => Customer::factory(),
            'appointment_id' => null,
            'proposed_starts_at' => $proposedStartsAt,
            'proposed_ends_at' => $proposedEndsAt,
            'token' => Str::random(32),
            'status' => $this->faker->randomElement(['pending', 'accepted', 'rejected', 'expired']),
        ];
    }
}
