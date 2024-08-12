<?php

namespace Database\Seeders;

use App\Models\Enterprise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnterpriseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Enterprise::factory()->count(5)->create();
        // $users = User::whereBetween('id', [1, 5])->limit(5)->get();
        // $shuffledUsers = $users->shuffle();
        // foreach ($shuffledUsers as $user) {
        //     Enterprise::factory()->count(2)->create([
        //         'user_id' => $user->id,
        //     ]);
        // }
    }
}
