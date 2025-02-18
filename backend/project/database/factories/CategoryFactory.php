<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'enterprise_id' => 1,
            'type' => 'document',  // デフォルト値
            'name' => '',         // Seeder側で上書きされる
            'display_order' => 0, // Seeder側で上書きされる
        ];
    }
}
