<?php

namespace Database\Seeders;

use App\Models\BusinessCard;
use App\Models\Enterprise;
use App\Models\EnterpriseCustomerUser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnterpriseCustomerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 全出展者のテストデータを作成
        $enterprises = Enterprise::all();
        $users = User::all();
        $businessCards = BusinessCard::pluck('business_card_id')->toArray();

        // 除外した出展者以外からランダムにテストデータを作成したい場合
        // $excludeEnterpriseIds = [93544150, 86914186, 73328207];
        // $numberOfEnterprisesToSelect = 5; // 選択したい企業の数
        // $enterprises = Enterprise::whereNotIn('id', $excludeEnterpriseIds)
        //     ->inRandomOrder()
        //     ->take($numberOfEnterprisesToSelect)
        //     ->get();

        // テストデータを作成する出展者を指定したい場合
        // $selectedEnterpriseIds = [74934729, 40943199];
        // $enterprises = Enterprise::whereIn('id', $selectedEnterpriseIds)->get();

        foreach ($enterprises as $enterprise) {
            $randomUsers = $users->random(rand(1, 5)); // 1出展者あたり1人〜5人のユーザをランダムに選ぶ

            foreach ($randomUsers as $user) {
                $businessCardId = null;
                if (!empty($businessCards)) {
                    $randomIndex = array_rand($businessCards);
                    $businessCardId = $businessCards[$randomIndex];
                    // 使用したIDを配列から削除
                    unset($businessCards[$randomIndex]);
                }
                EnterpriseCustomerUser::firstOrCreate([ // 第1引数：検索条件
                    'enterprise_id' => $enterprise->id,
                    'user_id' => $user->id,
                ], [ // 第2引数：新規作成時の追加データ
                    'business_card_id' => $businessCardId,
                ]);
            }
        }
    }
}
