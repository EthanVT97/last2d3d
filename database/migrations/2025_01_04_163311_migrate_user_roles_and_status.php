<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Only update roles if the old columns exist
        if (Schema::hasColumn('users', 'is_admin')) {
            DB::table('users')->where('is_admin', true)->update([
                'role' => 'admin',
                'status' => DB::raw("CASE WHEN is_banned = 1 THEN 'banned' ELSE 'active' END")
            ]);
        }

        if (Schema::hasColumn('users', 'is_agent') && Schema::hasColumn('users', 'is_admin')) {
            DB::table('users')->where('is_agent', true)->where('is_admin', false)->update([
                'role' => 'agent',
                'status' => DB::raw("CASE WHEN is_banned = 1 THEN 'banned' ELSE 'active' END")
            ]);

            DB::table('users')->where('is_admin', false)->where('is_agent', false)->update([
                'role' => 'user',
                'status' => DB::raw("CASE WHEN is_banned = 1 THEN 'banned' ELSE 'active' END")
            ]);
        }

        // Drop the old boolean columns if they exist
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_admin')) {
                $table->dropColumn('is_admin');
            }
            if (Schema::hasColumn('users', 'is_agent')) {
                $table->dropColumn('is_agent');
            }
            if (Schema::hasColumn('users', 'is_banned')) {
                $table->dropColumn('is_banned');
            }
        });

        // Set default role and status for any users that don't have them
        DB::table('users')->whereNull('role')->update(['role' => 'user']);
        DB::table('users')->whereNull('status')->update(['status' => 'active']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_admin')) {
                $table->boolean('is_admin')->default(false);
            }
            if (!Schema::hasColumn('users', 'is_agent')) {
                $table->boolean('is_agent')->default(false);
            }
            if (!Schema::hasColumn('users', 'is_banned')) {
                $table->boolean('is_banned')->default(false);
            }
        });

        // Only update boolean flags if role exists
        if (Schema::hasColumn('users', 'role')) {
            DB::table('users')->where('role', 'admin')->update([
                'is_admin' => true,
                'is_agent' => false,
                'is_banned' => DB::raw("CASE WHEN status = 'banned' THEN 1 ELSE 0 END")
            ]);

            DB::table('users')->where('role', 'agent')->update([
                'is_admin' => false,
                'is_agent' => true,
                'is_banned' => DB::raw("CASE WHEN status = 'banned' THEN 1 ELSE 0 END")
            ]);

            DB::table('users')->where('role', 'user')->update([
                'is_admin' => false,
                'is_agent' => false,
                'is_banned' => DB::raw("CASE WHEN status = 'banned' THEN 1 ELSE 0 END")
            ]);
        }

        // Drop the role and status columns
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'status']);
        });
    }
};
