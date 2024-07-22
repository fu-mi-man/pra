<?php

namespace Database\Seeders;

use App\Models\CatalogPage;
use Illuminate\Database\Seeder;

class CatalogPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CatalogPage::factory()->count(100)->create();
    }
}
