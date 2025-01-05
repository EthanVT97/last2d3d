<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'phone' => '09987654321',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'commission_rate' => 0,
            'commission_balance' => 0,
            'referral_code' => 'ADMIN001'
        ]);
    }
}
