<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('approved_by')->nullable()->after('status')
                ->constrained('users')->onDelete('set null');
            $table->foreignId('rejected_by')->nullable()->after('approved_by')
                ->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable()->after('rejected_by');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
            $table->text('admin_note')->nullable()->after('rejected_at');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropColumn([
                'approved_by',
                'rejected_by',
                'approved_at',
                'rejected_at',
                'admin_note'
            ]);
        });
    }
};
