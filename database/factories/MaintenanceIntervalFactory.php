<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaintenanceInterval>
 */
class MaintenanceIntervalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->paragraph(),
            'interval_months' => $this->faker->randomElement([3, 6, 12]),
            'booking_window_days' => $this->faker->numberBetween(14, 60),
            'estimated_duration_minutes' => $this->faker->numberBetween(30, 180),
        ];
    }
}
