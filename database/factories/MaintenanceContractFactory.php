<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Customer;
use App\Models\MaintenanceInterval;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaintenanceContract>
 */
class MaintenanceContractFactory extends Factory
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
            'customer_id' => Customer::factory(),
            'maintenance_interval_id' => MaintenanceInterval::factory(),
            'start_date' => $this->faker->date(),
            'active' => true,
            'notes' => $this->faker->paragraph(),
        ];
    }
}
