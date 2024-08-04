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
        // enterpriseテーブルから全件取得
        $enterprises = Enterprise::all();

        foreach ($enterprises as $enterprise) {
            // enterprise_userテーブルに同じ組み合わせを挿入
            EnterpriseUser::create([
                'user_id' => $enterprise->user_id,
                'enterprise_id' => $enterprise->enterprise_id,
            ]);
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
//
//         EnterpriseUser::factory(20)->create();
    }
}
