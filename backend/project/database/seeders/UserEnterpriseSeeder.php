<?php

namespace Database\Seeders;

use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserEnterpriseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $enterprises = Enterprise::all();
    }
}
