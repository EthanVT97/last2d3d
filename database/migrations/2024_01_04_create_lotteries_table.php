<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lotteries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 2d, 3d, thai, laos
            $table->string('numbers');
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending'); // pending, won, lost
            $table->string('result')->nullable();
            $table->decimal('won_amount', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lotteries');
    }
};
