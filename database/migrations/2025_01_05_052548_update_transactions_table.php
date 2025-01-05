<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'status')) {
                $table->string('status')->default('pending')->after('type');
            }
            if (!Schema::hasColumn('transactions', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('status');
            }
            if (!Schema::hasColumn('transactions', 'proof')) {
                $table->string('proof')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('transactions', 'reference_id')) {
                $table->string('reference_id')->nullable()->after('proof');
            }
            if (!Schema::hasColumn('transactions', 'approval_level')) {
                $table->unsignedTinyInteger('approval_level')->default(1)->after('reference_id');
            }
            if (!Schema::hasColumn('transactions', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->after('approval_level')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('transactions', 'rejected_by')) {
                $table->foreignId('rejected_by')->nullable()->after('approved_by')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('transactions', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('rejected_by');
            }
            if (!Schema::hasColumn('transactions', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('approved_at');
            }
            if (!Schema::hasColumn('transactions', 'admin_note')) {
                $table->text('admin_note')->nullable()->after('rejected_at');
            }
            if (!Schema::hasColumn('transactions', 'metadata')) {
                $table->json('metadata')->nullable()->after('admin_note');
            }
            if (!Schema::hasColumn('transactions', 'deposit_account_id')) {
                $table->foreignId('deposit_account_id')->nullable()->after('metadata')->constrained()->nullOnDelete();
            }
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'payment_method',
                'proof',
                'reference_id',
                'approval_level',
                'approved_by',
                'rejected_by',
                'approved_at',
                'rejected_at',
                'admin_note',
                'metadata',
                'deposit_account_id'
            ]);
        });
    }
};
