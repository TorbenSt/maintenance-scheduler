<?php

namespace Database\Factories;

use App\Models\AppointmentProposal;
use App\Models\Company;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerResponse>
 */
class CustomerResponseFactory extends Factory
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
            'appointment_proposal_id' => AppointmentProposal::factory(),
            'response' => $this->faker->randomElement(['accepted', 'rejected']),
            'comment' => $this->faker->optional(0.5)->paragraph(),
        ];
    }
}
