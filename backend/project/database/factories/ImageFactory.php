<?php

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    protected $model = Image::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userId = $this->faker->numberBetween(1, 5);
        $date = $this->faker->dateTimeThisYear()->format('Y-m-d-His');

        return [
            'user_id' => $userId,
            'type' => $this->faker->randomElement(['product', 'catalog', 'crop', 'enterprise', 'profile']),
            'id' => "image/99999/{$userId}/4270b026/{$date}/XXXXXXX.pdf.page_1.jpg",
            'name' => $this->faker->words(3, true),
            'size' => $this->faker->numberBetween(1000, 9999),
        ];
    }
}
