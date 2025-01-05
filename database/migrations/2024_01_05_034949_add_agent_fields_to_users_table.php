<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_agent')) {
                $table->boolean('is_agent')->default(false);
            }
            if (!Schema::hasColumn('users', 'agent_code')) {
                $table->string('agent_code')->nullable()->unique();
            }
            if (!Schema::hasColumn('users', 'referrer_id')) {
                $table->foreignId('referrer_id')->nullable()->constrained('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_agent')) {
                $table->dropColumn('is_agent');
            }
            if (Schema::hasColumn('users', 'agent_code')) {
                $table->dropColumn('agent_code');
            }
            if (Schema::hasColumn('users', 'referrer_id')) {
                $table->dropForeign(['referrer_id']);
                $table->dropColumn('referrer_id');
            }
        });
    }
};
