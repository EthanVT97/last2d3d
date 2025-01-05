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
        // last_login_at field is already added in the initial users table migration
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to drop the last_login_at field as it's managed by the initial migration
    }
};
