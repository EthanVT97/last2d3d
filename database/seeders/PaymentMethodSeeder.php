<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run()
    {
        $methods = [
            [
                'name' => 'KBZ Pay',
                'code' => 'KBZPAY',
                'type' => 'mobile_banking',
                'phone' => '09123456789',
                'account_name' => 'John Doe',
                'min_amount' => 1000,
                'max_amount' => 1000000,
                'instructions' => 'ငွေလွှဲပြီးပါက screenshot ရိုက်၍ တင်ပေးပါ။',
                'is_active' => true
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
                'is_active' => true
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
                'is_active' => true
            ]
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(
                ['code' => $method['code']],
                $method
            );
        }
    }
}
