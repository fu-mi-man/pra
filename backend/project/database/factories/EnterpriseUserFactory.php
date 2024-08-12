<?php

namespace Database\Factories;

use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class EnterpriseUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return []; // このfactoryは何もしていない
        // return [
        //     'user_id' => User::factory(),
        //     'enterprise_id' => Enterprise::factory(),
        //     'is_completed' => fake()->boolean(),
        //     'is_cancelled' => fake()->boolean(),
        // ];
    }
}
