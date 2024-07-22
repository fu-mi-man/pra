<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $image = Image::factory()->create(['type' => 'product']);

        return [
            'sequence' => $this->faker->numberBetween(1, 10),
            'image_id' => $image->id,
        ];
    }
}
