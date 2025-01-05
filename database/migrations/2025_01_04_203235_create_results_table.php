<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 2d, 3d, thai, laos
            $table->string('number');
            $table->string('session')->nullable(); // morning, evening (for 2D)
            $table->timestamp('drawn_at');
            $table->json('metadata')->nullable(); // For additional data like first_three, last_three for Thai lottery
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
