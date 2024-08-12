<?php

namespace Database\Seeders;

use App\Models\Enterprise;
use App\Models\EnterpriseUser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnterpriseUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 全出展者のテストデータを作成
        $users = User::all();
        $enterprises = Enterprise::all();

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
                EnterpriseUser::firstOrCreate([ // 第1引数：検索条件
                    'user_id' => $user->id,
                    'enterprise_id' => $enterprise->id,
                ], [ // 第2引数：新規作成時の追加データ
                    'is_completed' => fake()->boolean(),
                    'is_cancelled' => fake()->boolean(),
                ]);
            }
        }
        // 20人のユーザーをランダムに選択
//         $users = User::inRandomOrder()->limit(5)->get();
//
//         // 10個のエンタープライズをランダムに選択
//         $enterprises = Enterprise::inRandomOrder()->limit(3)->get();
//
//         // ユーザーとエンタープライズをシャッフルしてランダムにマッチング
//         $shuffledUsers = $users->shuffle();
//         $shuffledEnterprises = $enterprises->shuffle();
//
//         foreach ($shuffledEnterprises as $index => $enterprise) {
//             // 各エンタープライズに対して、まだ割り当てられていないユーザーを選択
//             $user = $shuffledUsers[$index];
//
//             EnterpriseUser::create([
//                 'user_id' => $user->id,
//                 'enterprise_id' => $enterprise->enterprise_id,
//             ]);
//         }
        // 各ユーザーに対してランダムな数のエンタープライズを関連付ける
//         $users->each(function ($user) use ($enterprises) {
//             $user->enterprises()->attach(
//                 $enterprises->random(rand(1, 3))->pluck('id')->toArray()
//             );
//         });
    }
}
