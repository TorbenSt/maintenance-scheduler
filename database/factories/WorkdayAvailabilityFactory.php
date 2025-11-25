<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Technician;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkdayAvailability>
 */
class WorkdayAvailabilityFactory extends Factory
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
            'technician_id' => Technician::factory(),
            'date' => $this->faker->dateTimeBetween('+1 day', '+30 days')->format('Y-m-d'),
            'max_appointments' => $this->faker->numberBetween(4, 12),
            'is_day_off' => $this->faker->boolean(20),
        ];
    }
}
