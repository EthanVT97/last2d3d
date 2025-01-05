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
        // Phone field is already added in the initial users table migration
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to drop the phone field as it's managed by the initial migration
    }
};
