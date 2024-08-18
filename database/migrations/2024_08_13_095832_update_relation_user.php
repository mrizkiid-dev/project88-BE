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
        Schema::table('user_address', function (Blueprint $table) {
            $table->dropColumn('user_uuid');
        });

        Schema::table('shopping_session', function (Blueprint $table) {
            $table->dropColumn('user_uuid');
        });
        
        Schema::table('user_address', function (Blueprint $table) {
            $table->uuid('user_uuid');
            $table->foreign('user_uuid')->references('uuid')->on('user')->onUpdate('cascade')->onDelete('cascade');
        });
        Schema::table('shopping_session', function (Blueprint $table) {
            $table->uuid('user_uuid');
            $table->foreign('user_uuid')->references('uuid')->on('user')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_address', function (Blueprint $table) {
            $table->dropForeign(['user_uuid']);
        });
        Schema::table('shopping_session', function (Blueprint $table) {
            $table->dropForeign(['user_uuid']);
        });
    }
};
