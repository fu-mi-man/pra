<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EnterpriseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->unique()->numberBetween(10000000, 99999999),
            'name' => fake()->company(),
            'description' => fake()->realText(1000, 2),
            'address' => fake()->address(),
            'email' => fake()->companyEmail(),
            'icon' => fake()->optional()->imageUrl(100, 100),
            'header' => fake()->optional()->imageUrl(1200, 300),
            'is_published' => fake()->boolean(),
            'website' => fake()->optional()->url(),
            'site_id' => fake()->unique()->word(),
        ];
    }
}
