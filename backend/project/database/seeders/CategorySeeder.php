<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    private array $documentCategories = [
        '営業資料', '契約書', '請求書', '報告書',
        'マニュアル', '議事録', '企画書', '申請書'
    ];

    private array $productCategories = [
        'オフィス用品', '文具', 'パソコン', 'ソフトウェア',
        '家具', '事務機器', '消耗品', '備品'
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $enterpriseId = 1;

        foreach ($this->documentCategories as $index => $name) {
            Category::factory()->create([
                'enterprise_id' => $enterpriseId,
                'type' => 'document',
                'name' => $name,
                'display_order' => $index + 1
            ]);
        }

        foreach ($this->productCategories as $index => $name) {
            Category::factory()->create([
                'enterprise_id' => $enterpriseId,
                'type' => 'product',
                'name' => $name,
                'display_order' => $index + 1
            ]);
        }

        // Category::factory()->count(10)->create();
    }
}
