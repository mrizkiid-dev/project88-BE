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
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('order', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'paid',
                'send',
                'need_confirmation',
                'done',
                'cancel'
            ])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
