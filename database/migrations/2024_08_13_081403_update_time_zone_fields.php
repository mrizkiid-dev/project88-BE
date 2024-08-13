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
        Schema::table('product_category', function (Blueprint $table) {
            $table->timestampTz('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->change();
            $table->timestampTz('modified_at')->default(DB::raw('CURRENT_TIMESTAMP'))->change();
        });

        Schema::table('role', function (Blueprint $table) {
            $table->dropColumn('updated_at')->nullable();
        });

        Schema::table('role', function (Blueprint $table) {
            $table->timestampTz('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->change();
            $table->timestampTz('modified_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('user', function (Blueprint $table) {
            $table->timestampTz('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->change();
            $table->timestampTz('modified_at')->default(DB::raw('CURRENT_TIMESTAMP'))->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_category', function (Blueprint $table) {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('modified_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('role', function (Blueprint $table) {
            $table->timestampz('created_at')->nullable();
            $table->timestampz('update_at')->nullable();
        });

        Schema::table('user', function (Blueprint $table) {
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('modified_at')->nullable();
        });
    }
};
