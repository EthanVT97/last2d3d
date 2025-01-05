<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('approval_level')->default('admin')->after('status'); // admin, agent
            $table->boolean('needs_admin_approval')->default(false)->after('approval_level');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['approval_level', 'needs_admin_approval']);
        });
    }
};
