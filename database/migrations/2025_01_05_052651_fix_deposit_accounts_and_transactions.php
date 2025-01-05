<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Fix transactions table
        if (!Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->decimal('amount', 10, 2);
                $table->string('type'); // deposit, withdrawal, bet, win
                $table->string('status')->default('pending');
                $table->string('payment_method')->nullable();
                $table->string('proof')->nullable();
                $table->string('reference_id')->nullable();
                $table->unsignedTinyInteger('approval_level')->default(1);
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('approved_at')->nullable();
                $table->timestamp('rejected_at')->nullable();
                $table->text('admin_note')->nullable();
                $table->json('metadata')->nullable();
                $table->foreignId('deposit_account_id')->nullable();
                $table->timestamps();
            });
        } else {
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
                    $table->foreignId('deposit_account_id')->nullable()->after('metadata');
                }
            });
        }

        // Add default deposit accounts if none exist
        if (DB::table('deposit_accounts')->count() === 0) {
            DB::table('deposit_accounts')->insert([
                [
                    'account_name' => 'Aung Ko',
                    'account_number' => '0123456789',
                    'bank_name' => 'KBZ',
                    'status' => true,
                    'remarks' => 'ငွေလွှဲပြီးပါက ငွေလွှဲပြေစာကို ဓာတ်ပုံရိုက်၍ ပေးပို့ပါ။',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'account_name' => 'Aung Ko',
                    'account_number' => '9876543210',
                    'bank_name' => 'AYA',
                    'status' => true,
                    'remarks' => 'ငွေလွှဲပြီးပါက ငွေလွှဲပြေစာကို ဓာတ်ပုံရိုက်၍ ပေးပို့ပါ။',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'account_name' => 'Aung Ko',
                    'account_number' => '1122334455',
                    'bank_name' => 'WAVE',
                    'status' => true,
                    'remarks' => 'ငွေလွှဲပြီးပါက ငွေလွှဲပြေစာကို ဓာတ်ပုံရိုက်၍ ပေးပို့ပါ။',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }
    }

    public function down()
    {
        // No need for down method as we're just fixing existing tables
    }
};
