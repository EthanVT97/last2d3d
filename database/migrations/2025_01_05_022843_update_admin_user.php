<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Delete existing admin users
        DB::table('users')->where('role', 'admin')->delete();

        // Create new admin user
        DB::table('users')->insert([
            'name' => 'Admin',
            'phone' => '09402733199',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
            'points' => 0,
            'commission_rate' => 0,
            'commission_balance' => 0,
            'balance' => 0
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('phone', '09402733199')->delete();
    }
};
