<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_picture')->nullable();
            $table->string('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->unsignedBigInteger('preferred_payment_method')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'profile_picture',
                'address',
                'date_of_birth',
                'preferred_payment_method'
            ]);
        });
    }
};
