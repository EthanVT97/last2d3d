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
        // Check if admin user exists
        $adminExists = DB::table('users')->where('role', 'admin')->exists();

        if (!$adminExists) {
            // Create admin user
            DB::table('users')->insert([
                'name' => 'Admin User',
                'phone' => '09123456789',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do nothing in reverse migration
    }
};
