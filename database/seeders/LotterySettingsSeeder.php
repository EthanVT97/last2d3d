<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class LotterySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 3D lottery settings
        Setting::updateOrCreate(
            ['key' => 'lottery_settings', 'type' => '3d'],
            [
                'value' => [
                    'min_amount' => 100,
                    'max_amount' => 50000,
                    'max_amount_per_number' => 10000,
                    'disabled_numbers' => [],
                    'is_active' => true
                ]
            ]
        );

        // 2D lottery settings
        Setting::updateOrCreate(
            ['key' => 'lottery_settings', 'type' => '2d'],
            [
                'value' => [
                    'min_amount' => 100,
                    'max_amount' => 50000,
                    'max_amount_per_number' => 10000,
                    'disabled_numbers' => [],
                    'is_active' => true
                ]
            ]
        );

        // Thai lottery settings
        Setting::updateOrCreate(
            ['key' => 'lottery_settings', 'type' => 'thai'],
            [
                'value' => [
                    'min_amount' => 100,
                    'max_amount' => 50000,
                    'max_amount_per_number' => 10000,
                    'disabled_numbers' => [],
                    'is_active' => true
                ]
            ]
        );

        // Laos lottery settings
        Setting::updateOrCreate(
            ['key' => 'lottery_settings', 'type' => 'laos'],
            [
                'value' => [
                    'min_amount' => 100,
                    'max_amount' => 50000,
                    'max_amount_per_number' => 10000,
                    'disabled_numbers' => [],
                    'is_active' => true
                ]
            ]
        );
    }
}
