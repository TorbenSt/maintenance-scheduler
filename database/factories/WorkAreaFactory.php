<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkArea>
 */
class WorkAreaFactory extends Factory
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
            'center_lat' => $this->faker->latitude(47, 55),
            'center_lng' => $this->faker->longitude(6, 14),
            'radius_km' => $this->faker->numberBetween(5, 50),
        ];
    }
}
