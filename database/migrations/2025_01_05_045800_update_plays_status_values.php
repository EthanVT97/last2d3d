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
        // Update existing plays with old status values
        DB::table('plays')
            ->where('status', 'won')
            ->update(['status' => 'approved']);
            
        DB::table('plays')
            ->where('status', 'lost')
            ->update(['status' => 'rejected']);
            
        // Add a check constraint for the new status values
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE plays ADD CONSTRAINT plays_status_check CHECK (status IN ('pending', 'approved', 'rejected'))");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the check constraint
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE plays DROP CONSTRAINT plays_status_check");
        }
        
        // Revert status values back
        DB::table('plays')
            ->where('status', 'approved')
            ->update(['status' => 'won']);
            
        DB::table('plays')
            ->where('status', 'rejected')
            ->update(['status' => 'lost']);
    }
};
