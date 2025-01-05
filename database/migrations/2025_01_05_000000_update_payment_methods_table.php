<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            // Add new columns first
            if (!Schema::hasColumn('payment_methods', 'type')) {
                $table->string('type')->after('name')->default('kpay');
            }
            if (!Schema::hasColumn('payment_methods', 'phone')) {
                $table->string('phone')->after('type')->nullable();
            }
            if (!Schema::hasColumn('payment_methods', 'account_name')) {
                $table->string('account_name')->after('phone'); // Account holder name
            }
            
            // Drop columns that are no longer needed
            if (Schema::hasColumn('payment_methods', 'code')) {
                $table->dropColumn('code');
            }
            if (Schema::hasColumn('payment_methods', 'account_number')) {
                $table->dropColumn('account_number');
            }
            if (Schema::hasColumn('payment_methods', 'min_amount')) {
                $table->dropColumn('min_amount');
            }
            if (Schema::hasColumn('payment_methods', 'max_amount')) {
                $table->dropColumn('max_amount');
            }
            if (Schema::hasColumn('payment_methods', 'instructions')) {
                $table->dropColumn('instructions');
            }
        });
    }

    public function down()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            // Remove new columns
            if (Schema::hasColumn('payment_methods', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('payment_methods', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('payment_methods', 'account_name')) {
                $table->dropColumn('account_name');
            }

            // Restore old columns
            if (!Schema::hasColumn('payment_methods', 'code')) {
                $table->string('code')->after('name');
            }
            if (!Schema::hasColumn('payment_methods', 'account_number')) {
                $table->string('account_number')->after('code');
            }
            if (!Schema::hasColumn('payment_methods', 'min_amount')) {
                $table->decimal('min_amount', 12, 2)->after('account_number');
            }
            if (!Schema::hasColumn('payment_methods', 'max_amount')) {
                $table->decimal('max_amount', 12, 2)->after('min_amount');
            }
            if (!Schema::hasColumn('payment_methods', 'instructions')) {
                $table->text('instructions')->after('max_amount')->nullable();
            }
        });
    }
};
