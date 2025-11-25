<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id'  => Company::factory(),
            'first_name'  => $this->faker->firstName(),
            'last_name'   => $this->faker->lastName(),
            'email'       => $this->faker->safeEmail(),
            'phone'       => $this->faker->phoneNumber(),
            'street'      => $this->faker->streetAddress(),
            'postal_code' => $this->faker->postcode(),
            'city'        => $this->faker->city(),
            'country'     => 'DE',
            'lat'         => $this->faker->latitude(47, 55),
            'lng'         => $this->faker->longitude(6, 14),
        ];
    }
}
