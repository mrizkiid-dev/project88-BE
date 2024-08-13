<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('product_category');
            $table->string('SKU');
            $table->string('name');
            $table->text('desc');
            $table->bigInteger('price');
            $table->float('discount')->nullable();
            $table->bigInteger('qty')->default(0);
            $table->bigInteger('sell_out')->default(0);
            $table->bigInteger('weight')->default(100);
            $table->timestampTz('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestampTz('modified_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    public function down()
    {
        Schema::dropIfExists('product');
    }
};
