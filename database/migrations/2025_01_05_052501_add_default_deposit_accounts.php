<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
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

    public function down()
    {
        DB::table('deposit_accounts')->truncate();
    }
};
