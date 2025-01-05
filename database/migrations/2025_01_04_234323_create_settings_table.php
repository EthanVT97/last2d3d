<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->json('value');
            $table->string('type')->nullable();
            $table->timestamps();

            // Add unique constraint for key-type combination
            $table->unique(['key', 'type']);
        });

        // Insert default lottery settings
        DB::table('settings')->insert([
            [
                'key' => 'lottery_settings',
                'type' => '2d',
                'value' => json_encode([
                    'min_amount' => 100,
                    'max_amount' => 10000,
                    'min_number' => 0,
                    'max_number' => 99,
                    'is_active' => true
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'lottery_settings',
                'type' => '3d',
                'value' => json_encode([
                    'min_amount' => 100,
                    'max_amount' => 10000,
                    'min_number' => 0,
                    'max_number' => 999,
                    'is_active' => true
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
