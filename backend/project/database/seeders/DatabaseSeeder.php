<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 商品
        $this->call([
            ProductImageSeeder::class,
            CatalogPageSeeder::class,
        ]);
        // ユーザと出展者
        $this->call([
            UserSeeder::class,
            EnterpriseSeeder::class,
            EnterpriseUserSeeder::class,
            BusinessCardSeeder::class,
            EnterpriseCustomerUserSeeder::class,
            CategorySeeder::class,
        ]);
    }
}
