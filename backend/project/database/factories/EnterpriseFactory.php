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
            'user_id' => User::factory(),
            'name' => $this->faker->company,
            'description' => $this->faker->paragraph,
            'address' => $this->faker->address,
            'phone_number' => $this->faker->phoneNumber,
            'email' => $this->faker->companyEmail,
            'website' => $this->faker->url,
            'founded_at' => $this->faker->date(),
            'industry' => $this->faker->word,
            'employee_count' => $this->faker->numberBetween(1, 10000),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
