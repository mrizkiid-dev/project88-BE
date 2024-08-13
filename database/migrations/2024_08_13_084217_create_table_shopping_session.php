<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shopping_session', function (Blueprint $table) {
            $table->id();
            $table->float('total_payment')->default(0);
            $table->uuid('user_uuid')->constrained('user', 'uuid');
            $table->float('sub_total')->nullable();
            $table->smallInteger('is_done')->default(0);
            $table->timestampTz('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestampTz('modified_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('shopping_session');
    }
};
