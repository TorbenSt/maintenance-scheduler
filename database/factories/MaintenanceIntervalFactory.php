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
     * Vordefinierte Wartungsintervalle für Heizungs- und Energiesysteme
     */
    private static array $maintenanceTypes = [
        [
            'name' => 'Ölheizung Wartung',
            'description' => 'Jährliche Wartung der Ölheizungsanlage inklusive Inspektion, Reinigung und Sicherheitsprüfung',
            'interval_months' => 12,
            'estimated_duration_minutes' => 120,
        ],
        [
            'name' => 'Gasheizung Wartung',
            'description' => 'Jährliche Wartung und Überprüfung der Gasheizungsanlage gemäß Herstellerangaben',
            'interval_months' => 12,
            'estimated_duration_minutes' => 90,
        ],
        [
            'name' => 'Pelletheizung Wartung',
            'description' => 'Halbjährliche Wartung der Pelletheizung mit Brennerreinigung und Systemprüfung',
            'interval_months' => 6,
            'estimated_duration_minutes' => 150,
        ],
        [
            'name' => 'Wärmepumpe Wartung',
            'description' => 'Jährliche Inspektion und Wartung der Wärmepumpenanlage',
            'interval_months' => 12,
            'estimated_duration_minutes' => 100,
        ],
        [
            'name' => 'Solaranlage Wartung',
            'description' => 'Halbjährliche Überprüfung und Wartung der Solaranlage (Kollektoren, Leitungen, Ausdehnungsgefäß)',
            'interval_months' => 6,
            'estimated_duration_minutes' => 120,
        ],
        [
            'name' => 'Speicherheizung Wartung',
            'description' => 'Jährliche Überprüfung der Speicherheizung und Thermostat-Kalibrierung',
            'interval_months' => 12,
            'estimated_duration_minutes' => 60,
        ],
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $maintenance = $this->faker->randomElement(self::$maintenanceTypes);

        return [
            'company_id' => Company::factory(),
            'name' => $maintenance['name'],
            'description' => $maintenance['description'],
            'interval_months' => $maintenance['interval_months'],
            'booking_window_days' => $this->faker->numberBetween(14, 60),
            'estimated_duration_minutes' => $maintenance['estimated_duration_minutes'],
        ];
    }

    /**
     * Erstelle 2-6 unterschiedliche MaintenanceIntervals für eine Company
     */
    public function forCompany(int $count = null): self
    {
        $count = $count ?? $this->faker->numberBetween(2, 6);
        
        return $this->state(function (array $attributes) use ($count) {
            return $attributes;
        })->sequence(...array_map(
            fn () => ['name' => null], // placeholder
            range(1, $count)
        ));
    }
}
