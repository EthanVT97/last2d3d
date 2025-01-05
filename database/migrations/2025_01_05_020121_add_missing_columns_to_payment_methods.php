<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToPaymentMethods extends Migration
{
    public function up()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_methods', 'code')) {
                $table->string('code')->unique()->after('name');
            }
            if (!Schema::hasColumn('payment_methods', 'type')) {
                $table->string('type')->default('mobile_banking')->after('code');
            }
            if (!Schema::hasColumn('payment_methods', 'min_amount')) {
                $table->decimal('min_amount', 12, 2)->default(1000)->after('account_name');
            }
            if (!Schema::hasColumn('payment_methods', 'max_amount')) {
                $table->decimal('max_amount', 12, 2)->default(1000000)->after('min_amount');
            }
            if (!Schema::hasColumn('payment_methods', 'instructions')) {
                $table->text('instructions')->nullable()->after('max_amount');
            }
            if (!Schema::hasColumn('payment_methods', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('instructions');
            }
        });
    }

    public function down()
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn(['code', 'type', 'min_amount', 'max_amount', 'instructions', 'is_active']);
        });
    }
}
