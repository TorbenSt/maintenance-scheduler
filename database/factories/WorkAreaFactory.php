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
            // Deutschland: ca. 47.3°N - 55.1°N (Breite), 5.9°E - 15.0°E (Länge)
            'center_lat' => $this->faker->latitude(47.3, 55.1),
            'center_lng' => $this->faker->longitude(5.9, 15.0),
            'radius_km' => $this->faker->numberBetween(5, 50),
        ];
    }
}
