<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Enterprise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    private array $catalogCategories = [
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
        // 全件取得
        // $enterprises = Enterprise::all();
        // ランダムに5件の出展者を取得
        $enterprises = Enterprise::inRandomOrder()->limit(5)->get();
        foreach ($enterprises as $enterprise) {
            foreach ($this->catalogCategories as $index => $name) {
                Category::factory()->create([
                    'enterprise_id' => $enterprise->id,
                    'type' => 'catalog',
                    'name' => $name,
                    'display_order' => $index + 1
                ]);
            }

            foreach ($this->productCategories as $index => $name) {
                Category::factory()->create([
                    'enterprise_id' => $enterprise->id,
                    'type' => 'product',
                    'name' => $name,
                    'display_order' => $index + 1
                ]);
            }
        }
        // Category::factory()->count(10)->create();
    }
}
