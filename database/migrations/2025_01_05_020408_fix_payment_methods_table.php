<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('payment_methods')) {
            Schema::create('payment_methods', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique();
                $table->string('type')->default('mobile_banking');
                $table->string('phone')->nullable();
                $table->string('account_name');
                $table->decimal('min_amount', 12, 2)->default(1000);
                $table->decimal('max_amount', 12, 2)->default(1000000);
                $table->text('instructions')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Insert default payment methods
        DB::table('payment_methods')->insertOrIgnore([
            [
                'name' => 'KBZ Pay',
                'code' => 'KBZPAY',
                'type' => 'mobile_banking',
                'phone' => '09123456789',
                'account_name' => 'John Doe',
                'min_amount' => 1000,
                'max_amount' => 1000000,
                'instructions' => 'ငွေလွှဲပြီးပါက screenshot ရိုက်၍ တင်ပေးပါ။',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Wave Pay',
                'code' => 'WAVEPAY',
                'type' => 'mobile_banking',
                'phone' => '09987654321',
                'account_name' => 'Jane Doe',
                'min_amount' => 1000,
                'max_amount' => 500000,
                'instructions' => 'ငွေလွှဲပြီးပါက screenshot ရိုက်၍ တင်ပေးပါ။',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'CB Pay',
                'code' => 'CBPAY',
                'type' => 'mobile_banking',
                'phone' => '09555666777',
                'account_name' => 'Bob Smith',
                'min_amount' => 5000,
                'max_amount' => 1000000,
                'instructions' => 'ငွေလွှဲပြီးပါက screenshot ရိုက်၍ တင်ပေးပါ။',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
};
