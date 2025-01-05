<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all existing pending plays to approved
        DB::table('plays')->where('status', 'pending')->update(['status' => 'approved']);

        // Change default status to approved
        Schema::table('plays', function (Blueprint $table) {
            $table->string('status')->default('approved')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plays', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
        });
    }
};
