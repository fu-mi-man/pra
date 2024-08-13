<?php

namespace Database\Seeders;

use App\Models\BusinessCard;
use App\Models\EnterpriseCustomerUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusinessCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 名刺データを作成
        BusinessCard::factory()->count(5)->create();

        $enterpriseCustomerUsers = EnterpriseCustomerUser::all();
        foreach ($enterpriseCustomerUsers as $user) {
            if ($user->business_card_id) {
                BusinessCard::firstOrCreate(
                    // 第1引数：検索条件
                    ['business_card_id' => $user->business_card_id],
                    // 第2引数：新規作成時の追加データ
                    [
                        'name' => null,
                        'company' => null,
                        'position' => null,
                        'department' => null,
                        'address' => null,
                        'phone_number' => null,
                        'email' => null,
                        'memo' => 'aaaa',
                    ]
                );
            }
        }
    }
}
