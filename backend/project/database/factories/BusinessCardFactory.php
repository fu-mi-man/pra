<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BusinessCardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => null,
            'company' => null,
            'position' => null,
            'department' => null,
            'address' => null,
            'phone_number' => null,
            'email' => null,
            'memo' => fake()->realText(255, 2),
        ];
    }
}
