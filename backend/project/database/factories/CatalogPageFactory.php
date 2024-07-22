<?php

namespace Database\Factories;

use App\Models\CatalogPage;
use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CatalogPage>
 */
class CatalogPageFactory extends Factory
{
    protected $model = CatalogPage::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $image = Image::factory()->create(['type' => 'catalog']);

        return [
            'image_id' => $image->id,
        ];
    }
}
