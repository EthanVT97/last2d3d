<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EnsureAdminUserExists extends Migration
{
    public function up()
    {
        DB::table('users')->where('phone', '09123456789')->delete();
        
        DB::table('users')->insert([
            'name' => 'Admin',
            'phone' => '09123456789',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
            'balance' => 0,
        ]);
    }

    public function down()
    {
        DB::table('users')->where('phone', '09123456789')->delete();
    }
}
