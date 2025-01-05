<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lottery_results', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->datetime('draw_time');
            $table->json('numbers');
            $table->decimal('prize_amount', 12, 2);
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('created_by');
            $table->datetime('published_at')->nullable();
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lottery_results');
    }
};
