<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Company;
use App\Models\MaintenanceContract;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaintenanceTask>
 */
class MaintenanceTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dueDate = $this->faker->date();
        $bookingWindowStart = $this->faker->dateTimeBetween('-30 days', $dueDate)->format('Y-m-d');
        $bookingWindowEnd = $this->faker->dateTimeBetween($dueDate, '+30 days')->format('Y-m-d');

        return [
            'company_id' => Company::factory(),
            'maintenance_contract_id' => MaintenanceContract::factory(),
            'due_date' => $dueDate,
            'booking_window_start' => $bookingWindowStart,
            'booking_window_end' => $bookingWindowEnd,
            'status' => $this->faker->randomElement(['pending', 'proposed', 'confirmed', 'completed', 'cancelled']),
            'appointment_id' => null,
        ];
    }
}
