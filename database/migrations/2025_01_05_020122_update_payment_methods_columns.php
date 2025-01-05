<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePaymentMethodsColumns extends Migration
{
    public function up()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_methods', 'type')) {
                $table->string('type')->default('mobile_banking')->after('code');
            }
            if (!Schema::hasColumn('payment_methods', 'phone')) {
                $table->string('phone')->nullable()->after('type');
            }
            
            // Rename account_number to phone if it exists
            if (Schema::hasColumn('payment_methods', 'account_number')) {
                $table->renameColumn('account_number', 'phone');
            }
        });
    }

    public function down()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            if (Schema::hasColumn('payment_methods', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('payment_methods', 'phone')) {
                $table->dropColumn('phone');
            }
            if (!Schema::hasColumn('payment_methods', 'account_number')) {
                $table->string('account_number')->nullable();
            }
        });
    }
}
